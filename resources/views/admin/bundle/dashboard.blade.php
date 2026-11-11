<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium uppercase tracking-wide text-teal-700">Katalog perjalanan</p>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Kelola Paket Wisata</h2>
        </div>
    </x-slot>

    <div class="space-y-6 p-4 sm:p-6">
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

        <section class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h3 class="mb-4 text-lg font-semibold">Buat paket wisata</h3>
            <form method="POST" action="{{ route('admin.bundles.store') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @csrf
                <label class="text-sm font-medium">Nama paket
                    <input name="name" value="{{ old('name') }}" required maxlength="255" class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <label class="text-sm font-medium">Harga per peserta (Rp)
                    <input name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" required class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <label class="text-sm font-medium">Kuota peserta (kosong = tidak terbatas)
                    <input name="slot" type="number" min="0" value="{{ old('slot') }}" class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="text-sm font-medium">Durasi (hari)
                        <input name="duration_days" type="number" min="1" max="365" value="{{ old('duration_days', 1) }}" required class="mt-1 block w-full rounded-lg border-slate-300">
                    </label>
                    <label class="text-sm font-medium">Malam
                        <input name="duration_nights" type="number" min="0" max="364" value="{{ old('duration_nights', 0) }}" required class="mt-1 block w-full rounded-lg border-slate-300">
                    </label>
                </div>
                <label class="text-sm font-medium">Tanggal keberangkatan (opsional)
                    <input name="departure_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('departure_date') }}" class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <label class="text-sm font-medium">Foto paket (JPG, PNG, WebP; maks. 5 MB)
                    <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="mt-1 block w-full text-sm">
                </label>
                <fieldset class="rounded-lg border border-slate-200 p-3 md:col-span-2 xl:col-span-3">
                    <legend class="px-1 text-sm font-semibold">Destinasi (pilih minimal satu)</legend>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($destinations as $destination)
                            <label class="flex items-center gap-2 text-sm">
                                <input name="destination_ids[]" type="checkbox" value="{{ $destination->id }}" @checked(in_array($destination->id, old('destination_ids', []))) class="rounded border-slate-300 text-teal-700">
                                {{ $destination->name }}
                            </label>
                        @endforeach
                    </div>
                    @if ($destinations->isEmpty())
                        <p class="text-sm text-amber-700">Tambahkan destinasi terlebih dahulu.</p>
                    @endif
                </fieldset>
                <fieldset class="rounded-lg border border-slate-200 p-3 md:col-span-2 xl:col-span-3">
                    <legend class="px-1 text-sm font-semibold">Tiket yang termasuk</legend>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($tickets as $ticket)
                            <label class="flex items-center gap-2 text-sm">
                                <input name="ticket_ids[]" type="checkbox" value="{{ $ticket->id }}" @checked(in_array($ticket->id, old('ticket_ids', []))) class="rounded border-slate-300 text-teal-700">
                                {{ $ticket->name }} — {{ $ticket->destination->name }} (Rp {{ number_format($ticket->price, 0, ',', '.') }})
                            </label>
                        @endforeach
                    </div>
                    @if ($tickets->isEmpty())
                        <p class="text-sm text-slate-500">Tiket bersifat opsional. Buat tiket terlebih dahulu untuk memasukkannya.</p>
                    @endif
                </fieldset>
                <label class="text-sm font-medium md:col-span-2 xl:col-span-3">Deskripsi
                    <textarea name="description" rows="3" required class="mt-1 block w-full rounded-lg border-slate-300">{{ old('description') }}</textarea>
                </label>
                <label class="text-sm font-medium md:col-span-2 xl:col-span-3">Fasilitas (satu fasilitas per baris)
                    <textarea name="facilities" rows="3" placeholder="Transportasi&#10;Pemandu lokal&#10;Makan siang" class="mt-1 block w-full rounded-lg border-slate-300">{{ old('facilities') }}</textarea>
                </label>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input name="is_active" type="checkbox" value="1" checked class="rounded border-slate-300 text-teal-700">
                    Paket tersedia untuk booking
                </label>
                <button type="submit" class="rounded-lg bg-teal-700 px-5 py-2.5 font-semibold text-white hover:bg-teal-800 md:justify-self-start">Simpan paket</button>
            </form>
        </section>

        <form method="GET" action="{{ route('admin.bundles') }}" class="flex flex-col gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 sm:flex-row">
            <label class="sr-only" for="bundle-search">Cari paket</label>
            <input id="bundle-search" name="q" value="{{ $filters['q'] ?? '' }}" type="search" placeholder="Cari nama, deskripsi, atau destinasi" class="w-full rounded-lg border-slate-300">
            <button type="submit" class="rounded-lg bg-slate-800 px-5 py-2 font-semibold text-white">Cari</button>
            <a href="{{ route('admin.bundles') }}" class="rounded-lg border border-slate-300 px-5 py-2 text-center text-slate-700">Reset</a>
        </form>

        <div class="space-y-4">
            @forelse ($bundles as $bundle)
                <article class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="grid gap-5 p-5 md:grid-cols-[12rem_1fr_auto]">
                        <img src="{{ $bundle->image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($bundle->image_path) : asset('images/3.png') }}"
                            alt="{{ $bundle->name }}" class="h-40 w-full rounded-lg object-cover" loading="lazy">
                        <div>
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <h3 class="text-xl font-semibold text-slate-900">{{ $bundle->name }}</h3>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $bundle->is_active ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $bundle->is_active ? 'Tersedia' : 'Tidak tersedia' }}
                                </span>
                            </div>
                            <p class="mt-1 font-semibold text-teal-800">Rp {{ number_format($bundle->price, 0, ',', '.') }} / peserta</p>
                            <p class="mt-1 text-sm text-slate-600">
                                {{ $bundle->duration_days }} hari / {{ $bundle->duration_nights }} malam
                                · Berangkat: {{ $bundle->departure_date?->format('d M Y') ?? 'Pilih saat booking' }}
                                · Sisa kuota: {{ $bundle->slot === null ? 'Tidak terbatas' : number_format($bundle->slot) }}
                            </p>
                            <p class="mt-2 text-sm text-slate-600">{{ $bundle->description }}</p>
                            <p class="mt-2 text-sm"><strong>Destinasi:</strong> {{ $bundle->destinations->pluck('name')->join(', ') }}</p>
                            <p class="mt-1 text-sm"><strong>Tiket:</strong> {{ $bundle->tickets->pluck('name')->join(', ') ?: 'Tidak ada tiket yang dipilih' }}</p>
                            <p class="mt-1 text-sm"><strong>Fasilitas:</strong> {{ collect($bundle->facilities ?? [])->join(', ') ?: 'Belum ditentukan' }}</p>
                            <p class="mt-1 text-xs text-slate-500">Riwayat booking: {{ $bundle->bookings->count() }}</p>
                        </div>
                        <div class="flex gap-3 md:flex-col">
                            <a href="{{ route('payment', $bundle) }}" class="font-semibold text-teal-800 hover:underline">Lihat detail</a>
                            @if ($bundle->bookings->isEmpty())
                                <form method="POST" action="{{ route('admin.bundles.destroy', $bundle) }}" onsubmit="return confirm('Hapus paket {{ addslashes($bundle->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-rose-700 hover:underline">Hapus</button>
                                </form>
                            @else
                                <span class="text-xs text-slate-500">Tidak dapat dihapus karena sudah memiliki booking.</span>
                            @endif
                        </div>
                    </div>

                    <details class="border-t border-slate-100 px-5 py-3">
                        <summary class="cursor-pointer font-semibold text-teal-800">Edit paket</summary>
                        <form method="POST" action="{{ route('admin.bundles.update', $bundle) }}" enctype="multipart/form-data" class="mt-4 grid gap-4 rounded-lg bg-slate-50 p-4 md:grid-cols-2 xl:grid-cols-3">
                            @csrf
                            @method('PATCH')
                            <label class="text-sm font-medium">Nama paket
                                <input name="name" value="{{ $bundle->name }}" required maxlength="255" class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <label class="text-sm font-medium">Harga per peserta (Rp)
                                <input name="price" type="number" min="0" step="0.01" value="{{ $bundle->price }}" required class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <label class="text-sm font-medium">Kuota tersisa (kosong = tidak terbatas)
                                <input name="slot" type="number" min="0" value="{{ $bundle->slot }}" class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="text-sm font-medium">Durasi (hari)
                                    <input name="duration_days" type="number" min="1" max="365" value="{{ $bundle->duration_days }}" required class="mt-1 block w-full rounded-lg border-slate-300">
                                </label>
                                <label class="text-sm font-medium">Malam
                                    <input name="duration_nights" type="number" min="0" max="364" value="{{ $bundle->duration_nights }}" required class="mt-1 block w-full rounded-lg border-slate-300">
                                </label>
                            </div>
                            <label class="text-sm font-medium">Tanggal keberangkatan
                                <input name="departure_date" type="date" value="{{ $bundle->departure_date?->format('Y-m-d') }}" class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <label class="text-sm font-medium">Ganti foto paket
                                <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="mt-1 block w-full text-sm">
                            </label>
                            <fieldset class="rounded-lg border border-slate-200 bg-white p-3 md:col-span-2 xl:col-span-3">
                                <legend class="px-1 text-sm font-semibold">Destinasi</legend>
                                <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach ($destinations as $destination)
                                        <label class="flex items-center gap-2 text-sm">
                                            <input name="destination_ids[]" type="checkbox" value="{{ $destination->id }}" @checked($bundle->destinations->contains('id', $destination->id)) class="rounded border-slate-300 text-teal-700">
                                            {{ $destination->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                            <fieldset class="rounded-lg border border-slate-200 bg-white p-3 md:col-span-2 xl:col-span-3">
                                <legend class="px-1 text-sm font-semibold">Tiket termasuk</legend>
                                <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach ($tickets as $ticket)
                                        <label class="flex items-center gap-2 text-sm">
                                            <input name="ticket_ids[]" type="checkbox" value="{{ $ticket->id }}" @checked($bundle->tickets->contains('id', $ticket->id)) class="rounded border-slate-300 text-teal-700">
                                            {{ $ticket->name }} — {{ $ticket->destination->name }} (Rp {{ number_format($ticket->price, 0, ',', '.') }})
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                            <label class="text-sm font-medium md:col-span-2 xl:col-span-3">Deskripsi
                                <textarea name="description" rows="3" required class="mt-1 block w-full rounded-lg border-slate-300">{{ $bundle->description }}</textarea>
                            </label>
                            <label class="text-sm font-medium md:col-span-2 xl:col-span-3">Fasilitas (satu per baris)
                                <textarea name="facilities" rows="3" class="mt-1 block w-full rounded-lg border-slate-300">{{ collect($bundle->facilities ?? [])->join("\n") }}</textarea>
                            </label>
                            <label class="flex items-center gap-2 text-sm font-medium">
                                <input name="is_active" type="checkbox" value="1" @checked($bundle->is_active) class="rounded border-slate-300 text-teal-700">
                                Paket tersedia untuk booking
                            </label>
                            <button type="submit" class="rounded-lg bg-teal-700 px-5 py-2.5 font-semibold text-white md:justify-self-start">Simpan perubahan</button>
                        </form>
                    </details>
                </article>
            @empty
                <p class="rounded-xl bg-white p-6 text-slate-600">Belum ada paket wisata yang cocok.</p>
            @endforelse
        </div>
        {{ $bundles->links() }}
    </div>
</x-app-layout>
