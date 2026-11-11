<x-head>
    <main class="min-h-screen bg-slate-50 text-slate-900">
        <x-navbar />

        <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:py-16">
            <a href="{{ route('packages') }}" class="text-sm font-semibold text-teal-800 hover:underline">&larr; Kembali ke paket wisata</a>

            <div class="mt-5 grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
                <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                    <img src="{{ $bundle->image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($bundle->image_path) : asset('images/1.png') }}"
                        alt="{{ $bundle->name }}" class="h-64 w-full object-cover sm:h-96">
                    <div class="space-y-5 p-6 sm:p-8">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-teal-800">Paket wisata</p>
                            <h1 class="mt-1 text-3xl font-semibold sm:text-4xl">{{ $bundle->name }}</h1>
                        </div>
                        <p class="whitespace-pre-line leading-7 text-slate-600">{{ $bundle->description }}</p>

                        <dl class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-lg bg-slate-50 p-4">
                                <dt class="text-sm text-slate-500">Durasi</dt>
                                <dd class="mt-1 font-semibold">{{ $bundle->duration_days }} hari / {{ $bundle->duration_nights }} malam</dd>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-4">
                                <dt class="text-sm text-slate-500">Keberangkatan</dt>
                                <dd class="mt-1 font-semibold">{{ $bundle->departure_date?->format('d M Y') ?? 'Pilih tanggal saat booking' }}</dd>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-4">
                                <dt class="text-sm text-slate-500">Sisa kuota</dt>
                                <dd class="mt-1 font-semibold">{{ $bundle->slot === null ? 'Tidak terbatas' : number_format($bundle->slot) . ' peserta' }}</dd>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-4">
                                <dt class="text-sm text-slate-500">Status paket</dt>
                                <dd class="mt-1 font-semibold text-emerald-800">Tersedia</dd>
                            </div>
                        </dl>

                        <div>
                            <h2 class="font-semibold">Destinasi</h2>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @forelse ($bundle->destinations as $destination)
                                    <span class="rounded-full bg-teal-50 px-3 py-1 text-sm text-teal-900">{{ $destination->name }}</span>
                                @empty
                                    <span class="text-sm text-slate-500">Detail destinasi belum tersedia.</span>
                                @endforelse
                            </div>
                        </div>

                        <div>
                            <h2 class="font-semibold">Fasilitas</h2>
                            @forelse ($bundle->facilities ?? [] as $facility)
                                <span class="mt-2 mr-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">{{ $facility }}</span>
                            @empty
                                <p class="mt-1 text-sm text-slate-500">Informasi fasilitas belum ditambahkan.</p>
                            @endforelse
                        </div>

                        <div>
                            <h2 class="font-semibold">Tiket yang termasuk</h2>
                            <ul class="mt-2 space-y-2">
                                @forelse ($bundle->tickets as $ticket)
                                    <li class="rounded-lg border border-slate-100 p-3">
                                        <span class="font-medium">{{ $ticket->name }}</span>
                                        <span class="text-sm text-slate-600"> · {{ $ticket->destination->name }} · {{ $ticket->type }}</span>
                                    </li>
                                @empty
                                    <li class="text-sm text-slate-500">Paket ini belum memiliki tiket tambahan.</li>
                                @endforelse
                            </ul>
                            <p class="mt-2 text-xs text-slate-500">Harga paket sudah ditentukan admin dan tidak ditambahkan ulang dari harga tiket.</p>
                        </div>
                    </div>
                </article>

                <aside id="booking-form" class="h-fit scroll-mt-24 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
                    <p class="text-sm font-semibold uppercase tracking-wide text-teal-800">Mulai perjalananmu</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">Rp {{ number_format($bundle->price, 0, ',', '.') }}</p>
                    <p class="text-sm text-slate-500">per peserta · total dihitung otomatis di server</p>

                    @if ($errors->any())
                        <div role="alert" class="mt-5 rounded-lg bg-rose-50 p-4 text-sm text-rose-800">
                            <ul class="list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @auth
                        <form method="POST" action="{{ route('client.booking.store') }}" class="mt-6 space-y-4">
                            @csrf
                            <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
                            <input type="hidden" name="idempotency_key" value="{{ $bookingToken }}">
                            <label class="block text-sm font-medium">Nama pemesan
                                <input name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" required maxlength="255" class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <label class="block text-sm font-medium">Email
                                <input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" autocomplete="email" required maxlength="255" class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <label class="block text-sm font-medium">Nomor telepon
                                <input name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" placeholder="+62..." required maxlength="30" pattern="[0-9+(). -]{8,30}" class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <label class="block text-sm font-medium">Tanggal keberangkatan
                                <input name="departure_date" type="date" value="{{ old('departure_date', $bundle->departure_date?->format('Y-m-d')) }}"
                                    min="{{ now()->toDateString() }}" @if ($bundle->departure_date) readonly @endif required class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <label class="block text-sm font-medium">Jumlah peserta
                                <input name="participants" type="number" value="{{ old('participants', 1) }}" min="1"
                                    @if ($bundle->slot !== null) max="{{ $bundle->slot }}" @endif required class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <div class="rounded-lg bg-amber-50 p-3 text-sm text-amber-900">
                                Booking akan berstatus <strong>menunggu</strong> dan pembayaran <strong>belum dibayar</strong>.
                                Admin akan mengonfirmasi pembayaran secara manual; tidak ada pembayaran online di situs ini.
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-teal-700 px-5 py-3 font-semibold text-white transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2">
                                Booking Sekarang
                            </button>
                        </form>
                    @else
                        <div class="mt-6 rounded-lg bg-slate-50 p-4">
                            <p class="text-sm text-slate-600">Masuk atau daftar untuk mengisi detail pemesanan.</p>
                            <a href="{{ route('packages.booking', $bundle) }}" class="mt-3 inline-flex w-full justify-center rounded-lg bg-teal-700 px-5 py-3 font-semibold text-white hover:bg-teal-800">Masuk untuk booking</a>
                        </div>
                    @endauth
                </aside>
            </div>
        </section>
    </main>
</x-head>
