<x-head>
    <main class="min-h-screen bg-white text-slate-900">
        <section id="hero" class="relative isolate flex min-h-[92vh] items-center overflow-hidden bg-slate-950 text-white">
            <div class="absolute inset-0 -z-20 bg-cover bg-center" style="background-image: url('{{ asset('images/cd11556e-4fed-4fc5-b592-8f97cfdf2a15.png') }}'); background-size: cover; background-position: center;"></div>
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-slate-950/85 via-slate-950/60 to-slate-950/30"></div>
            <x-navbar />

            <div class="mx-auto w-full max-w-7xl px-4 pb-14 pt-32 sm:px-6 lg:px-8">
                <div class="max-w-3xl" style="animation: travel-rise 700ms ease-out both">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-teal-200">Perjalanan yang terasa dekat</p>
                    <h1 class="mt-5 text-4xl font-semibold leading-tight tracking-tight sm:text-6xl lg:text-7xl">
                        Dunia menunggu untuk Anda jelajahi.
                    </h1>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-200 sm:text-xl">
                        Temukan paket wisata pilihan, destinasi lokal, dan perjalanan yang dirancang agar setiap momen lebih berarti.
                    </p>
                </div>

                <form method="GET" action="{{ route('packages') }}" class="mt-10 rounded-2xl bg-white p-4 text-slate-900 shadow-2xl sm:p-5" aria-label="Cari paket wisata">
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1.4fr_1.2fr_1fr_1fr_auto]">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Nama paket
                            <input name="q" value="{{ request('q') }}" placeholder="Cari perjalanan..." class="mt-2 w-full rounded-lg border-slate-200 py-3 text-sm font-normal normal-case placeholder:text-slate-400 focus:border-teal-700 focus:ring-teal-700">
                        </label>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Destinasi
                            <select name="destination_id" class="mt-2 w-full rounded-lg border-slate-200 py-3 text-sm font-normal normal-case focus:border-teal-700 focus:ring-teal-700">
                                <option value="">Semua destinasi</option>
                                @foreach ($destinations as $destination)
                                    <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Harga minimum
                            <input name="min_price" type="number" min="0" step="1000" placeholder="Rp 0" class="mt-2 w-full rounded-lg border-slate-200 py-3 text-sm font-normal normal-case focus:border-teal-700 focus:ring-teal-700">
                        </label>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Harga maksimum
                            <input name="max_price" type="number" min="0" step="1000" placeholder="Tanpa batas" class="mt-2 w-full rounded-lg border-slate-200 py-3 text-sm font-normal normal-case focus:border-teal-700 focus:ring-teal-700">
                        </label>
                        <button type="submit" class="self-end rounded-lg bg-teal-700 px-6 py-3 font-semibold text-white transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2">
                            Cari paket
                        </button>
                    </div>
                </form>

                <a href="#featured" class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-white/90 hover:text-white">
                    Jelajahi perjalanan unggulan <span aria-hidden="true">&darr;</span>
                </a>
            </div>
        </section>

        <section id="featured" class="scroll-mt-24 px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-7xl">
                <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-800">Pilihan perjalanan</p>
                        <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Paket wisata unggulan</h2>
                    </div>
                    <a href="{{ route('packages') }}" class="font-semibold text-teal-800 hover:underline">Lihat semua paket <span aria-hidden="true">&rarr;</span></a>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($featuredBundles as $bundle)
                        <article class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                            <a href="{{ route('payment', $bundle) }}" class="block overflow-hidden">
                                <img src="{{ $bundle->image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($bundle->image_path) : asset('images/3.png') }}"
                                    alt="{{ $bundle->name }}" loading="lazy" class="h-52 w-full object-cover transition duration-500 group-hover:scale-105">
                            </a>
                            <div class="p-5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-teal-800">{{ $bundle->duration_days }} hari · {{ $bundle->duration_nights }} malam</p>
                                <h3 class="mt-2 text-lg font-semibold">{{ $bundle->name }}</h3>
                                <p class="mt-2 line-clamp-2 min-h-12 text-sm leading-6 text-slate-600">{{ $bundle->description }}</p>
                                <p class="mt-3 text-sm text-slate-500">{{ $bundle->destinations->pluck('name')->join(', ') ?: 'Destinasi segera diumumkan' }}</p>
                                <div class="mt-5 flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                                    <p class="font-semibold text-teal-800">Rp {{ number_format($bundle->price, 0, ',', '.') }}<span class="block text-xs font-normal text-slate-500">per orang</span></p>
                                    <a href="{{ route('packages.booking', $bundle) }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800">Pesan sekarang</a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl bg-slate-50 p-8 text-slate-600 sm:col-span-2 lg:col-span-4">
                            Paket wisata belum tersedia. Silakan kembali lagi nanti.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="destinations" class="scroll-mt-24 bg-slate-50 px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto mb-10 max-w-2xl text-center">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-800">Dari database Travel Yuk</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Destinasi untuk cerita berikutnya</h2>
                    <p class="mt-3 text-slate-600">Jelajahi tempat wisata pilihan dan temukan paket yang membawa Anda ke sana.</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($destinations as $destination)
                        <article class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:shadow-lg">
                            <img src="{{ $destination->image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image_path) : asset('images/images.jpg') }}"
                                alt="{{ $destination->name }}" loading="lazy" class="h-56 w-full object-cover transition duration-500 group-hover:scale-105">
                            <div class="p-5">
                                <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-900">{{ ucfirst($destination->category) }}</span>
                                <h3 class="mt-3 text-lg font-semibold">{{ $destination->name }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ $destination->address }}</p>
                                @if ($destination->description)
                                    <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">{{ $destination->description }}</p>
                                @endif
                                <a href="{{ route('packages', ['destination_id' => $destination->id]) }}" class="mt-4 inline-flex font-semibold text-teal-800 hover:underline">Cari paket di sini <span aria-hidden="true" class="ml-1">&rarr;</span></a>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl bg-white p-8 text-center text-slate-600 sm:col-span-2 lg:col-span-4">
                            Destinasi wisata akan ditampilkan setelah admin menambahkan data.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-800">Mengapa Travel Yuk</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Perjalanan direncanakan dengan lebih mudah</h2>
                </div>
                <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        ['01', 'Pilihan terkurasi', 'Paket dan destinasi dikelola langsung agar informasi perjalanan lebih jelas.'],
                        ['02', 'Harga transparan', 'Harga paket ditampilkan sebelum Anda membuat pemesanan.'],
                        ['03', 'Booking tercatat', 'Kode booking dan status pemesanan tersimpan di akun Anda.'],
                        ['04', 'Dukungan admin', 'Tim admin membantu konfirmasi jadwal dan pembayaran secara langsung.'],
                    ] as [$number, $title, $description])
                        <article class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-teal-300 hover:shadow-md">
                            <span class="text-sm font-bold text-teal-700">{{ $number }}</span>
                            <h3 class="mt-4 text-lg font-semibold">{{ $title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $description }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="stories" class="scroll-mt-24 bg-teal-950 px-4 py-16 text-white sm:px-6 lg:px-8 lg:py-20">
            <div class="mx-auto max-w-7xl">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Cerita pelanggan</p>
                        <h2 class="mt-2 text-3xl font-semibold">Pengalaman dari para pelancong</h2>
                    </div>
                    @auth
                        <a href="{{ route('client.booking') }}" class="font-semibold text-teal-100 underline underline-offset-4">Lihat booking saya</a>
                    @endauth
                </div>
                <p class="mt-6 max-w-2xl text-teal-100">
                    Ulasan pelanggan belum tersedia. Kami tidak menampilkan testimoni contoh sebagai ulasan nyata.
                </p>
            </div>
        </section>

        <section class="px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 rounded-3xl bg-teal-700 px-6 py-10 text-white sm:px-10 lg:flex-row lg:items-center lg:px-14">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-100">Waktunya berangkat</p>
                    <h2 class="mt-2 max-w-2xl text-3xl font-semibold tracking-tight sm:text-4xl">Temukan perjalanan yang sesuai dengan Anda.</h2>
                </div>
                <a href="{{ route('packages') }}" class="shrink-0 rounded-full bg-white px-6 py-3 font-semibold text-teal-900 transition hover:bg-teal-50">Jelajahi paket</a>
            </div>
        </section>

        <footer id="contact" class="scroll-mt-20 bg-slate-950 px-4 py-12 text-slate-300 sm:px-6 lg:px-8">
            <div class="mx-auto grid max-w-7xl gap-10 sm:grid-cols-2 lg:grid-cols-4">
                <div class="sm:col-span-2">
                    <img src="{{ asset('images/WLogo_nocut.svg') }}" alt="Travel Yuk" class="h-12 w-auto">
                    <p class="mt-4 max-w-md text-sm leading-6 text-slate-400">Temukan pengalaman baru, jelajahi destinasi pilihan, dan rencanakan perjalanan Anda bersama Travel Yuk.</p>
                </div>
                <div>
                    <h2 class="font-semibold text-white">Jelajahi</h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('packages') }}" class="hover:text-white">Paket wisata</a></li>
                        <li><a href="{{ route('index') }}#destinations" class="hover:text-white">Destinasi</a></li>
                        <li><a href="{{ route('index') }}#stories" class="hover:text-white">Cerita pelanggan</a></li>
                    </ul>
                </div>
                <div>
                    <h2 class="font-semibold text-white">Akun & kontak</h2>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white">Daftar</a></li>
                        <li><a href="{{ route('booking') }}" class="hover:text-white">Status booking &amp; konfirmasi admin</a></li>
                    </ul>
                </div>
            </div>
            <div class="mx-auto mt-10 max-w-7xl border-t border-white/10 pt-5 text-xs text-slate-500">
                &copy; {{ now()->year }} Travel Yuk. Semua hak dilindungi.
            </div>
        </footer>
    </main>
</x-head>
