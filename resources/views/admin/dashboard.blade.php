<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium uppercase tracking-wide text-teal-700">Travel Yuk</p>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Ringkasan Admin</h2>
        </div>
    </x-slot>

    <div class="space-y-6 p-4 sm:p-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ([
                ['Tempat wisata', $destinationCount, route('admin.destinations')],
                ['Tiket wisata', $ticketCount, route('admin.tickets')],
                ['Paket wisata', $bundleCount, route('admin.bundles')],
                ['Paket tersedia', $availableBundleCount, route('admin.bundles')],
                ['Booking', $bookingCount, route('admin.booking')],
            ] as [$label, $count, $href])
                <a href="{{ $href }}" class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($count) }}</p>
                </a>
            @endforeach
        </section>

        <section class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-5">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Booking terbaru</h3>
                    <p class="text-sm text-slate-600">{{ number_format($pendingBookingCount) }} booking menunggu tindakan admin</p>
                </div>
                <a href="{{ route('admin.booking') }}" class="font-semibold text-teal-800 hover:underline">Kelola booking</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Pemesan</th>
                            <th class="px-5 py-3">Paket</th>
                            <th class="px-5 py-3">Peserta</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentBookings as $booking)
                            <tr>
                                <td class="px-5 py-4 font-medium">{{ $booking->booking_code ?: '—' }}</td>
                                <td class="px-5 py-4">{{ $booking->name }}<span class="block text-xs text-slate-500">{{ $booking->email }}</span></td>
                                <td class="px-5 py-4">{{ $booking->bundle?->name ?? 'Paket telah dihapus' }}</td>
                                <td class="px-5 py-4">{{ $booking->participants }}</td>
                                <td class="px-5 py-4">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td class="px-5 py-4">{{ ucfirst($booking->status) }} · {{ $booking->payment_status === 'paid' ? 'Lunas' : 'Belum dibayar' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-8 text-center text-slate-500">Belum ada booking.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('admin.destinations') }}" class="rounded-lg border border-slate-200 bg-white p-4 font-semibold text-slate-800 hover:border-teal-600">Kelola tempat wisata</a>
            <a href="{{ route('admin.tickets') }}" class="rounded-lg border border-slate-200 bg-white p-4 font-semibold text-slate-800 hover:border-teal-600">Kelola tiket</a>
            <a href="{{ route('admin.bundles') }}" class="rounded-lg border border-slate-200 bg-white p-4 font-semibold text-slate-800 hover:border-teal-600">Kelola paket wisata</a>
            <a href="{{ route('admin.users.dashboard') }}" class="rounded-lg border border-slate-200 bg-white p-4 font-semibold text-slate-800 hover:border-teal-600">Kelola pengguna</a>
        </section>
    </div>
</x-app-layout>
