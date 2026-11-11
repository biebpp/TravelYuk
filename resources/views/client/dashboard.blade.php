<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium uppercase tracking-wide text-teal-700">Perjalanan Anda</p>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </div>
    </x-slot>

    <div class="space-y-6 p-4 sm:p-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-600">Total booking</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $bookingCount }}</p>
            </article>
            <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-600">Menunggu konfirmasi</p>
                <p class="mt-2 text-3xl font-semibold text-amber-700">{{ $pendingBookingCount }}</p>
            </article>
        </div>

        <section class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Booking terbaru</h3>
                    <p class="mt-1 text-sm text-slate-600">Pantau status pemesanan dan konfirmasi admin.</p>
                </div>
                <a href="{{ route('client.booking') }}" class="font-semibold text-teal-800 hover:underline">Semua booking</a>
            </div>

            <div class="mt-5 divide-y divide-slate-100">
                @forelse ($bookings as $booking)
                    <article class="flex flex-wrap items-center justify-between gap-3 py-4 first:pt-0 last:pb-0">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-teal-800">{{ $booking->booking_code ?: 'Booking lama' }}</p>
                            <h4 class="mt-1 font-semibold text-slate-900">{{ $booking->bundle?->name ?? 'Paket telah dihapus' }}</h4>
                            <p class="mt-1 text-sm text-slate-600">{{ $booking->participants }} peserta · Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $booking->status === 'pending' ? 'bg-amber-50 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </article>
                @empty
                    <div class="py-8 text-center">
                        <p class="font-semibold text-slate-900">Belum ada booking</p>
                        <p class="mt-1 text-sm text-slate-600">Pilih paket wisata untuk memulai perjalanan Anda.</p>
                        <a href="{{ route('packages') }}" class="mt-4 inline-flex rounded-lg bg-teal-700 px-5 py-2.5 font-semibold text-white hover:bg-teal-800">Cari paket</a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
