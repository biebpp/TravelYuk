<x-head>
    <main class="min-h-screen bg-slate-50 text-slate-900">
        <x-navbar />
        <section class="mx-auto max-w-3xl px-4 pb-12 pt-32 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-wide text-teal-800">Informasi pembayaran</p>
                <h1 class="mt-2 text-3xl font-semibold">Pembayaran dikonfirmasi secara manual</h1>
                <p class="mt-3 leading-7 text-slate-600">
                    Travel Yuk belum menggunakan payment gateway. Jangan mengirim pembayaran melalui formulir di situs ini.
                    Admin akan menghubungi Anda untuk konfirmasi pembayaran.
                </p>
                <dl class="mt-6 grid gap-4 rounded-xl bg-slate-50 p-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-slate-500">Kode booking</dt>
                        <dd class="mt-1 font-semibold">{{ $booking->booking_code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Paket wisata</dt>
                        <dd class="mt-1 font-semibold">{{ $booking->bundle->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Total</dt>
                        <dd class="mt-1 font-semibold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Status</dt>
                        <dd class="mt-1 font-semibold">{{ $booking->payment_status === 'paid' ? 'Sudah dibayar' : 'Belum dibayar' }}</dd>
                    </div>
                </dl>
                <a href="{{ route('client.booking') }}" class="mt-6 inline-flex rounded-lg bg-teal-700 px-5 py-3 font-semibold text-white transition hover:bg-teal-800">
                    Kembali ke riwayat booking
                </a>
            </div>
        </section>
    </main>
</x-head>
