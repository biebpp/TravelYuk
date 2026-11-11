<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Destination;
use App\Models\Ticket;
use App\Models\TourBundle;
use App\Services\BookingInventory;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function __construct(private readonly BookingInventory $inventory)
    {
    }

    public function home()
    {
        $featuredBundles = TourBundle::with(['destinations', 'tickets.destination'])
            ->where('is_active', true)
            ->where(fn (Builder $query) => $query->whereNull('slot')->orWhere('slot', '>', 0))
            ->latest()
            ->limit(4)
            ->get();

        $destinations = Destination::query()
            ->where('is_featured', true)
            ->orderBy('name')
            ->limit(8)
            ->get();

        if ($destinations->isEmpty()) {
            $destinations = Destination::query()->orderBy('name')->limit(8)->get();
        }

        return view('welcome', compact('featuredBundles', 'destinations'));
    }

    public function index()
    {
        $bookings = Booking::with([
            'bundle.destinations',
            'bundle.tickets.destination',
            'items.ticket',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('client.booking', compact('bookings'));
    }

    public function dashboard()
    {
        $bookings = Booking::with('bundle')
            ->where('user_id', Auth::id())
            ->latest()
            ->limit(5)
            ->get();
        $bookingCount = Booking::where('user_id', Auth::id())->count();
        $pendingBookingCount = Booking::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->count();

        return view('client.dashboard', compact('bookings', 'bookingCount', 'pendingBookingCount'));
    }

    public function packagesIndex(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'destination_id' => ['nullable', 'integer', 'exists:destinations,id'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'sort' => ['nullable', 'in:latest,price_asc,price_desc'],
        ]);

        if (isset($filters['min_price'], $filters['max_price']) && $filters['max_price'] < $filters['min_price']) {
            throw ValidationException::withMessages([
                'max_price' => 'Harga maksimum harus sama atau lebih besar dari harga minimum.',
            ]);
        }

        $bundles = TourBundle::with(['destinations', 'tickets.destination'])
            ->where('is_active', true)
            ->where(fn (Builder $query) => $query->whereNull('slot')->orWhere('slot', '>', 0))
            ->when($filters['q'] ?? null, function (Builder $query, string $search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('destinations', function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('address', 'like', "%{$search}%");
                        });
                });
            })
            ->when($filters['destination_id'] ?? null, function (Builder $query, int $destinationId) {
                $query->whereHas('destinations', fn (Builder $query) => $query->whereKey($destinationId));
            })
            ->when(isset($filters['min_price']), fn (Builder $query) => $query->where('price', '>=', $filters['min_price']))
            ->when(isset($filters['max_price']), fn (Builder $query) => $query->where('price', '<=', $filters['max_price']))
            ->when(($filters['sort'] ?? 'latest') === 'price_asc', fn (Builder $query) => $query->orderBy('price'))
            ->when(($filters['sort'] ?? 'latest') === 'price_desc', fn (Builder $query) => $query->orderByDesc('price'))
            ->when(($filters['sort'] ?? 'latest') === 'latest', fn (Builder $query) => $query->latest())
            ->paginate(9)
            ->withQueryString();

        $destinations = Destination::orderBy('name')->get();

        return view('pages.packages.index', compact('bundles', 'destinations', 'filters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bundle_id' => ['required', 'integer', 'exists:tour_bundles,id'],
            'idempotency_key' => ['required', 'uuid'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\s().-]{8,30}$/'],
            'email' => ['required', 'email', 'max:255'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'participants' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $existingBooking = Booking::where('idempotency_key', $validated['idempotency_key'])->first();
        if ($existingBooking) {
            abort_unless($existingBooking->user_id === Auth::id(), 409);

            return redirect()->route('client.booking')->with('message', "Booking {$existingBooking->booking_code} sudah tersimpan.");
        }

        try {
            $booking = DB::transaction(function () use ($validated) {
                $bundle = TourBundle::query()->lockForUpdate()->findOrFail($validated['bundle_id']);
                $departureDate = now()->parse($validated['departure_date'])->startOfDay();

                if ($bundle->departure_date && ! $bundle->departure_date->isSameDay($departureDate)) {
                    throw ValidationException::withMessages([
                        'departure_date' => 'Tanggal keberangkatan harus sesuai jadwal paket.',
                    ]);
                }

                $ticketIds = $bundle->tickets()->pluck('tickets.id');
                $tickets = Ticket::query()
                    ->whereIn('id', $ticketIds)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                if ($tickets->count() !== $ticketIds->count()) {
                    throw ValidationException::withMessages([
                        'bundle_id' => 'Daftar tiket paket tidak lagi valid. Silakan coba kembali.',
                    ]);
                }

                foreach ($tickets as $ticket) {
                    if (($ticket->valid_from && $departureDate->lt($ticket->valid_from))
                        || ($ticket->valid_until && $departureDate->gt($ticket->valid_until))) {
                        throw ValidationException::withMessages([
                            'departure_date' => "Tanggal keberangkatan tidak berlaku untuk tiket {$ticket->name}.",
                        ]);
                    }
                }

                $reservation = $this->inventory->reserve($bundle, $tickets, $validated['participants']);

                $booking = Booking::create([
                    'user_id' => Auth::id(),
                    'bundle_id' => $bundle->id,
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'departure_date' => $departureDate->toDateString(),
                    'participants' => $validated['participants'],
                    'total_price' => $bundle->price * $validated['participants'],
                    'booking_code' => 'TY-' . now()->format('Ymd') . '-' . Str::upper(Str::random(10)),
                    'idempotency_key' => $validated['idempotency_key'],
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'inventory_reserved' => true,
                    'bundle_quota_reserved' => $reservation['bundle_quota_reserved'],
                ]);

                foreach ($tickets as $ticket) {
                    BookingItem::create([
                        'booking_id' => $booking->id,
                        'ticket_id' => $ticket->id,
                        'ticket_name' => $ticket->name,
                        'unit_price' => $ticket->price,
                        'quantity' => $validated['participants'],
                        'quota_reserved' => $reservation['ticket_quota_reserved'][$ticket->id],
                    ]);
                }

                return $booking;
            });
        } catch (UniqueConstraintViolationException $exception) {
            $booking = Booking::where('idempotency_key', $validated['idempotency_key'])->first();
            if (! $booking) {
                throw $exception;
            }

            abort_unless($booking->user_id === Auth::id(), 409);
        }

        return redirect()->route('client.booking')->with('message', "Booking {$booking->booking_code} berhasil dibuat. Status pembayaran: belum dibayar.");
    }

    public function destroy(Request $request, int $id)
    {
        DB::transaction(function () use ($request, $id) {
            $booking = Booking::query()
                ->where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->findOrFail($id);

            if (! in_array($booking->status, ['pending', 'payment'], true) || $booking->payment_status !== 'unpaid') {
                throw ValidationException::withMessages([
                    'booking' => 'Booking yang sudah dikonfirmasi atau dibayar tidak dapat dibatalkan sendiri.',
                ]);
            }

            $this->inventory->release($booking);
            $booking->update(['status' => 'cancelled']);
        });

        return back()->with('message', 'Booking dibatalkan dan kuota yang dipesan telah dikembalikan.');
    }
}
