<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium uppercase tracking-wide text-teal-700">Operasional</p>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Kelola Booking</h2>
        </div>
    </x-slot>

    <div class="space-y-5 p-4 sm:p-6">
        @if (session('message'))
            <div role="status" class="rounded-lg bg-emerald-50 p-4 text-emerald-800">{{ session('message') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-lg bg-rose-50 p-4 text-rose-800">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="GET" action="{{ route('admin.booking') }}" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 sm:grid-cols-[1fr_auto_auto_auto]">
            <label class="sr-only" for="booking-search">Cari booking</label>
            <input id="booking-search" name="q" value="{{ $filters['q'] ?? '' }}" type="search" placeholder="Kode, nama pemesan, email, atau paket" class="rounded-lg border-slate-300">
            <select name="status" aria-label="Filter status booking" class="rounded-lg border-slate-300">
                <option value="">Semua status booking</option>
                @foreach (['pending', 'accepted', 'declined', 'cancelled', 'payment'] as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <select name="payment_status" aria-label="Filter status pembayaran" class="rounded-lg border-slate-300">
                <option value="">Semua pembayaran</option>
                <option value="unpaid" @selected(($filters['payment_status'] ?? '') === 'unpaid')>Belum dibayar</option>
                <option value="paid" @selected(($filters['payment_status'] ?? '') === 'paid')>Sudah dibayar</option>
            </select>
            <div class="flex gap-2">
                <button class="rounded-lg bg-slate-800 px-4 py-2 font-semibold text-white" type="submit">Cari</button>
                <a href="{{ route('admin.booking') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-slate-700">Reset</a>
            </div>
        </form>

        <div class="space-y-4">
            @forelse ($bookings as $booking)
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-teal-800">{{ $booking->booking_code ?: 'Booking lama' }}</p>
                            <h3 class="mt-1 text-lg font-semibold">{{ $booking->bundle?->name ?? 'Paket telah dihapus' }}</h3>
                            <p class="mt-1 text-sm text-slate-700">{{ $booking->name }} · {{ $booking->email ?: $booking->user?->email }}</p>
                            <p class="text-sm text-slate-600">{{ $booking->phone ?: 'Nomor telepon belum tercatat' }}</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-sm text-slate-600">{{ $booking->departure_date?->format('d M Y') ?? 'Tanggal belum ditentukan' }}</p>
                            <p class="text-sm text-slate-600">{{ $booking->participants }} peserta</p>
                            <p class="mt-1 font-semibold text-teal-800">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if ($booking->items->isNotEmpty())
                        <div class="mt-3 border-t border-slate-100 pt-3">
                            <p class="text-sm font-semibold">Tiket termasuk</p>
                            <p class="mt-1 text-sm text-slate-600">
                                {{ $booking->items->map(fn ($item) => $item->ticket_name.' × '.$item->quantity)->join(', ') }}
                            </p>
                        </div>
                    @endif

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                        <p class="text-sm">
                            Status: <strong>{{ ucfirst($booking->status) }}</strong>
                            · Pembayaran: <strong>{{ $booking->payment_status === 'paid' ? 'Sudah dibayar' : 'Belum dibayar' }}</strong>
                        </p>

                        @if (! in_array($booking->status, ['declined', 'cancelled'], true))
                            <form method="POST" action="{{ route('admin.booking.status', $booking) }}" class="flex flex-wrap items-end gap-3">
                                @csrf
                                @method('PATCH')
                                <label class="text-xs font-medium">Status booking
                                    <select name="status" class="mt-1 block rounded-lg border-slate-300 text-sm">
                                        <option value="pending" @selected($booking->status === 'pending')>Menunggu</option>
                                        <option value="accepted" @selected($booking->status === 'accepted')>Dikonfirmasi</option>
                                        <option value="declined" @selected($booking->status === 'declined')>Ditolak</option>
                                    </select>
                                </label>
                                <label class="text-xs font-medium">Status pembayaran
                                    <select name="payment_status" class="mt-1 block rounded-lg border-slate-300 text-sm">
                                        <option value="unpaid" @selected($booking->payment_status === 'unpaid')>Belum dibayar</option>
                                        <option value="paid" @selected($booking->payment_status === 'paid')>Sudah dibayar</option>
                                    </select>
                                </label>
                                <button type="submit" class="rounded-lg bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">Simpan status</button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-xl bg-white p-8 text-center text-slate-600 shadow-sm ring-1 ring-slate-200">Tidak ada booking yang cocok.</div>
            @endforelse
        </div>
        {{ $bookings->links() }}
    </div>
</x-app-layout>
