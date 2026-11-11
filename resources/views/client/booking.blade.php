<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium uppercase tracking-wide text-teal-700">Perjalanan Anda</p>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Riwayat Booking</h2>
        </div>
    </x-slot>

    <div class="space-y-4 p-4 sm:p-6">
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

        @forelse ($bookings as $booking)
            <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-teal-800">{{ $booking->booking_code ?: 'Booking lama' }}</p>
                        <h3 class="mt-1 text-xl font-semibold">{{ $booking->bundle?->name ?? 'Paket telah dihapus' }}</h3>
                        @if ($booking->bundle)
                            <p class="mt-1 text-sm text-slate-600">{{ $booking->bundle->destinations->pluck('name')->join(', ') }}</p>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs font-semibold">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-800">Booking: {{ ucfirst($booking->status) }}</span>
                        <span class="rounded-full px-3 py-1 {{ $booking->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                            {{ $booking->payment_status === 'paid' ? 'Sudah dibayar' : 'Belum dibayar' }}
                        </span>
                    </div>
                </div>

                <dl class="mt-5 grid gap-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
                    <div><dt class="text-slate-500">Pemesan</dt><dd class="mt-1 font-medium">{{ $booking->name }} · {{ $booking->phone }}</dd></div>
                    <div><dt class="text-slate-500">Keberangkatan</dt><dd class="mt-1 font-medium">{{ $booking->departure_date?->format('d M Y') ?? 'Belum ditentukan' }}</dd></div>
                    <div><dt class="text-slate-500">Peserta</dt><dd class="mt-1 font-medium">{{ $booking->participants }}</dd></div>
                    <div><dt class="text-slate-500">Total</dt><dd class="mt-1 font-semibold text-teal-800">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</dd></div>
                </dl>

                @if ($booking->items->isNotEmpty())
                    <div class="mt-4">
                        <p class="text-sm font-semibold">Tiket paket</p>
                        <ul class="mt-1 list-inside list-disc text-sm text-slate-600">
                            @foreach ($booking->items as $item)
                                <li>{{ $item->ticket_name }} × {{ $item->quantity }} — Rp {{ number_format($item->unit_price, 0, ',', '.') }} / tiket</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                    <p class="text-xs text-slate-500">Dibuat {{ $booking->created_at->format('d M Y, H:i') }}</p>
                    @if (in_array($booking->status, ['pending', 'payment'], true) && $booking->payment_status === 'unpaid')
                        <form method="POST" action="{{ route('client.booking.destroy', $booking->id) }}" onsubmit="return confirm('Batalkan booking {{ addslashes($booking->booking_code ?? $booking->name) }}? Kuota akan dikembalikan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-50">Batalkan booking</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-xl bg-white p-10 text-center shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-semibold">Belum ada booking</h3>
                <p class="mt-1 text-sm text-slate-600">Temukan perjalanan yang sesuai dan pesan langsung dari katalog.</p>
                <a href="{{ route('packages') }}" class="mt-4 inline-flex rounded-lg bg-teal-700 px-5 py-2.5 font-semibold text-white hover:bg-teal-800">Jelajahi paket</a>
            </div>
        @endforelse

        {{ $bookings->links() }}
    </div>
</x-app-layout>
