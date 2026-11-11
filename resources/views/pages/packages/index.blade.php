<x-head>
    <main class="min-h-screen bg-slate-50 text-slate-900">
        <x-navbar />

        <section class="bg-teal-950 px-4 pb-10 pt-32 text-white sm:px-6 lg:px-8 lg:pb-14">
            <div class="mx-auto max-w-7xl">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-200">Rencanakan perjalananmu</p>
                <h1 class="mt-2 text-4xl font-semibold tracking-tight sm:text-5xl">Temukan paket wisata</h1>
                <p class="mt-3 max-w-2xl leading-7 text-teal-100">Bandingkan destinasi, tiket, jadwal, dan harga dalam satu paket. Gunakan pencarian dan filter untuk menemukan perjalanan yang tepat.</p>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
            <form method="GET" action="{{ route('packages') }}" class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-6">
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <label class="text-sm font-semibold text-slate-700">Nama paket atau destinasi
                        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Contoh: pantai, Bali" class="mt-2 w-full rounded-lg border-slate-300 font-normal focus:border-teal-700 focus:ring-teal-700">
                    </label>
                    <label class="text-sm font-semibold text-slate-700">Destinasi
                        <select name="destination_id" class="mt-2 w-full rounded-lg border-slate-300 font-normal focus:border-teal-700 focus:ring-teal-700">
                            <option value="">Semua destinasi</option>
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination->id }}" @selected(($filters['destination_id'] ?? null) == $destination->id)>{{ $destination->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-semibold text-slate-700">Harga minimum (Rp)
                        <input type="number" name="min_price" min="0" step="1000" value="{{ $filters['min_price'] ?? '' }}" placeholder="0" class="mt-2 w-full rounded-lg border-slate-300 font-normal focus:border-teal-700 focus:ring-teal-700">
                    </label>
                    <label class="text-sm font-semibold text-slate-700">Harga maksimum (Rp)
                        <input type="number" name="max_price" min="0" step="1000" value="{{ $filters['max_price'] ?? '' }}" placeholder="Tanpa batas" class="mt-2 w-full rounded-lg border-slate-300 font-normal focus:border-teal-700 focus:ring-teal-700">
                    </label>
                    <label class="text-sm font-semibold text-slate-700">Urutkan
                        <select name="sort" class="mt-2 w-full rounded-lg border-slate-300 font-normal focus:border-teal-700 focus:ring-teal-700">
                            <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>Terbaru</option>
                            <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Harga terendah</option>
                            <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Harga tertinggi</option>
                        </select>
                    </label>
                </div>
                @error('max_price')
                    <p class="mt-3 text-sm text-red-700">{{ $message }}</p>
                @enderror
                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <button class="rounded-lg bg-teal-700 px-5 py-3 font-semibold text-white transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2" type="submit">Terapkan filter</button>
                    <a href="{{ route('packages') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Reset filter</a>
                    <span class="text-sm text-slate-500">{{ $bundles->total() }} paket ditemukan</span>
                </div>
            </form>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($bundles as $bundle)
                    <article class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <a href="{{ route('payment', $bundle) }}" class="block overflow-hidden">
                            <img
                                src="{{ $bundle->image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($bundle->image_path) : asset('images/1.png') }}"
                                alt="{{ $bundle->name }}"
                                loading="lazy"
                                class="h-56 w-full object-cover transition duration-500 group-hover:scale-105"
                            >
                        </a>
                        <div class="flex flex-1 flex-col p-5 sm:p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-teal-800">{{ $bundle->duration_days }} hari · {{ $bundle->duration_nights }} malam</p>
                                    <h2 class="mt-2 text-xl font-semibold">{{ $bundle->name }}</h2>
                                </div>
                                <span class="shrink-0 rounded-full bg-teal-50 px-3 py-1 text-xs font-medium text-teal-800">
                                    {{ $bundle->slot === null ? 'Kuota fleksibel' : number_format($bundle->slot) . ' slot' }}
                                </span>
                            </div>

                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">{{ $bundle->description }}</p>

                            <div class="mt-4">
                                <p class="text-sm font-semibold text-slate-700">Destinasi</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @forelse ($bundle->destinations as $destination)
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">{{ $destination->name }}</span>
                                    @empty
                                        <span class="text-sm text-slate-500">Belum ada destinasi</span>
                                    @endforelse
                                </div>
                            </div>

                            @if ($bundle->departure_date)
                                <p class="mt-4 text-sm text-slate-600">Keberangkatan: <span class="font-medium text-slate-800">{{ $bundle->departure_date->format('d M Y') }}</span></p>
                            @endif

                            <div class="mt-5 border-t border-slate-100 pt-4">
                                <p class="text-sm font-semibold text-slate-700">Tiket termasuk</p>
                                @forelse ($bundle->tickets as $ticket)
                                    <p class="mt-1 text-sm text-slate-600">{{ $ticket->name }} <span class="text-slate-400">· {{ $ticket->destination->name }}</span></p>
                                @empty
                                    <p class="mt-1 text-sm text-slate-500">Konfirmasi detail tiket kepada admin.</p>
                                @endforelse
                            </div>

                            <div class="mt-auto flex items-center justify-between gap-3 border-t border-slate-100 pt-5">
                                <p class="font-semibold text-teal-800">Rp {{ number_format($bundle->price, 0, ',', '.') }} <span class="font-normal text-slate-500">/ orang</span></p>
                                <a href="{{ route('packages.booking', $bundle) }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800">Pesan sekarang</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl bg-white p-8 text-center text-slate-600 shadow-sm ring-1 ring-slate-200 sm:col-span-2 lg:col-span-3">
                        <p class="font-semibold">Paket tidak ditemukan.</p>
                        <p class="mt-1 text-sm">Coba ubah kata kunci atau rentang harga.</p>
                        <a href="{{ route('packages') }}" class="mt-4 inline-block font-semibold text-teal-800 hover:underline">Reset filter</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $bundles->links() }}</div>
        </section>
    </main>
</x-head>
