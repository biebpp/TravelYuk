<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium uppercase tracking-wide text-teal-700">Katalog perjalanan</p>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Kelola Tiket Wisata</h2>
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
            <h3 class="mb-4 text-lg font-semibold">Tambah tiket</h3>
            <form method="POST" action="{{ route('admin.tickets.store') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @csrf
                <label class="text-sm font-medium">Nama tiket
                    <input name="name" value="{{ old('name') }}" required maxlength="255" class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <label class="text-sm font-medium">Destinasi
                    <select name="destination_id" required class="mt-1 block w-full rounded-lg border-slate-300">
                        <option value="">Pilih destinasi</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->id }}" @selected(old('destination_id') == $destination->id)>{{ $destination->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-medium">Jenis tiket
                    <input name="type" value="{{ old('type', 'admission') }}" required maxlength="100" placeholder="Masuk, wahana, snorkeling..." class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <label class="text-sm font-medium">Harga (Rp)
                    <input name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" required class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <label class="text-sm font-medium">Sisa kuota (kosong = tidak terbatas)
                    <input name="quota" type="number" min="0" value="{{ old('quota') }}" class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="text-sm font-medium">Berlaku mulai
                        <input name="valid_from" type="date" value="{{ old('valid_from') }}" class="mt-1 block w-full rounded-lg border-slate-300">
                    </label>
                    <label class="text-sm font-medium">Berlaku sampai
                        <input name="valid_until" type="date" value="{{ old('valid_until') }}" class="mt-1 block w-full rounded-lg border-slate-300">
                    </label>
                </div>
                <label class="text-sm font-medium md:col-span-2 xl:col-span-3">Deskripsi
                    <textarea name="description" rows="2" class="mt-1 block w-full rounded-lg border-slate-300">{{ old('description') }}</textarea>
                </label>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input name="is_active" type="checkbox" value="1" checked class="rounded border-slate-300 text-teal-700">
                    Tiket aktif dan dapat dimasukkan ke paket
                </label>
                <button class="rounded-lg bg-teal-700 px-5 py-2.5 font-semibold text-white hover:bg-teal-800 md:justify-self-start" type="submit">Tambah tiket</button>
            </form>
        </section>

        <form method="GET" action="{{ route('admin.tickets') }}" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 sm:grid-cols-[1fr_auto_auto]">
            <label class="sr-only" for="ticket-search">Cari tiket</label>
            <input id="ticket-search" name="q" value="{{ $filters['q'] ?? '' }}" type="search" placeholder="Cari nama tiket atau destinasi" class="rounded-lg border-slate-300">
            <label class="sr-only" for="ticket-destination">Filter destinasi</label>
            <select id="ticket-destination" name="destination_id" class="rounded-lg border-slate-300">
                <option value="">Semua destinasi</option>
                @foreach ($destinations as $destination)
                    <option value="{{ $destination->id }}" @selected(($filters['destination_id'] ?? null) == $destination->id)>{{ $destination->name }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button class="rounded-lg bg-slate-800 px-4 py-2 font-semibold text-white" type="submit">Cari</button>
                <a href="{{ route('admin.tickets') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-slate-700">Reset</a>
            </div>
        </form>

        <div class="space-y-4">
            @forelse ($tickets as $ticket)
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">{{ $ticket->name }}</h3>
                            <p class="text-sm text-slate-600">{{ $ticket->destination->name }} · {{ $ticket->type }}</p>
                            <p class="mt-1 font-semibold text-teal-800">Rp {{ number_format($ticket->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-slate-600">
                                Kuota: {{ $ticket->quota === null ? 'Tidak terbatas' : number_format($ticket->quota) }}
                                · {{ $ticket->is_active ? 'Aktif' : 'Nonaktif' }}
                                @if ($ticket->valid_from || $ticket->valid_until)
                                    · Berlaku {{ $ticket->valid_from?->format('d M Y') ?? 'tanpa batas awal' }}–{{ $ticket->valid_until?->format('d M Y') ?? 'tanpa batas akhir' }}
                                @endif
                            </p>
                        </div>
                        <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" onsubmit="return confirm('Hapus tiket {{ addslashes($ticket->name) }}? Riwayat booking akan tetap menyimpan nama dan harga tiket.')">
                            @csrf
                            @method('DELETE')
                            <button class="text-sm font-semibold text-rose-700 hover:underline" type="submit">Hapus tiket</button>
                        </form>
                    </div>

                    <details>
                        <summary class="cursor-pointer text-sm font-semibold text-teal-800">Edit tiket</summary>
                        <form id="ticket-update-{{ $ticket->id }}" method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="mt-4 grid gap-3 rounded-lg bg-slate-50 p-4 md:grid-cols-2 xl:grid-cols-3">
                            @csrf
                            @method('PATCH')
                            <label class="text-sm font-medium">Nama tiket
                                <input name="name" value="{{ $ticket->name }}" required maxlength="255" class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <label class="text-sm font-medium">Destinasi
                                <select name="destination_id" required class="mt-1 block w-full rounded-lg border-slate-300">
                                    @foreach ($destinations as $destination)
                                        <option value="{{ $destination->id }}" @selected($ticket->destination_id === $destination->id)>{{ $destination->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="text-sm font-medium">Jenis tiket
                                <input name="type" value="{{ $ticket->type }}" required maxlength="100" class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <label class="text-sm font-medium">Harga (Rp)
                                <input name="price" type="number" min="0" step="0.01" value="{{ $ticket->price }}" required class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <label class="text-sm font-medium">Sisa kuota (kosong = tidak terbatas)
                                <input name="quota" type="number" min="0" value="{{ $ticket->quota }}" class="mt-1 block w-full rounded-lg border-slate-300">
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="text-sm font-medium">Berlaku mulai
                                    <input name="valid_from" type="date" value="{{ $ticket->valid_from?->format('Y-m-d') }}" class="mt-1 block w-full rounded-lg border-slate-300">
                                </label>
                                <label class="text-sm font-medium">Berlaku sampai
                                    <input name="valid_until" type="date" value="{{ $ticket->valid_until?->format('Y-m-d') }}" class="mt-1 block w-full rounded-lg border-slate-300">
                                </label>
                            </div>
                            <label class="text-sm font-medium md:col-span-2 xl:col-span-3">Deskripsi
                                <textarea name="description" rows="2" class="mt-1 block w-full rounded-lg border-slate-300">{{ $ticket->description }}</textarea>
                            </label>
                            <label class="flex items-center gap-2 text-sm font-medium">
                                <input name="is_active" type="checkbox" value="1" @checked($ticket->is_active) class="rounded border-slate-300 text-teal-700">
                                Tiket aktif
                            </label>
                            <button class="rounded-lg bg-teal-700 px-5 py-2.5 font-semibold text-white md:justify-self-start" type="submit">Simpan perubahan</button>
                        </form>
                    </details>
                </article>
            @empty
                <p class="rounded-xl bg-white p-6 text-slate-600">Belum ada tiket yang cocok. Buat destinasi terlebih dahulu jika daftar destinasi masih kosong.</p>
            @endforelse
        </div>
        {{ $tickets->links() }}
    </div>
</x-app-layout>
