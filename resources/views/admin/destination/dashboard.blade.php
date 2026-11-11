<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-teal-700">Katalog perjalanan</p>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Kelola Destinasi</h2>
            </div>
            <a href="#create-destination" class="rounded-lg bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">
                Tambah destinasi
            </a>
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

        <section id="create-destination" class="scroll-mt-24 rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h3 class="mb-4 text-lg font-semibold text-slate-900">Tambah tempat wisata</h3>
            <form method="POST" action="{{ route('admin.destinations.store') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
                @csrf
                <label class="text-sm font-medium text-slate-700">Nama destinasi
                    <input name="name" value="{{ old('name') }}" required maxlength="255" class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <label class="text-sm font-medium text-slate-700">Kategori
                    <input name="category" value="{{ old('category') }}" required maxlength="100" placeholder="Pantai, alam, budaya..." class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <label class="text-sm font-medium text-slate-700 md:col-span-2">Alamat atau lokasi
                    <input name="address" value="{{ old('address') }}" required maxlength="1000" class="mt-1 block w-full rounded-lg border-slate-300">
                </label>
                <label class="text-sm font-medium text-slate-700 md:col-span-2">Deskripsi
                    <textarea name="description" rows="3" maxlength="10000" class="mt-1 block w-full rounded-lg border-slate-300">{{ old('description') }}</textarea>
                </label>
                <label class="text-sm font-medium text-slate-700">Foto (JPG, PNG, WebP; maks. 5 MB)
                    <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="mt-1 block w-full text-sm">
                </label>
                <label class="flex items-center gap-2 self-end pb-2 text-sm font-medium text-slate-700">
                    <input name="is_featured" type="checkbox" value="1" @checked(old('is_featured')) class="rounded border-slate-300 text-teal-700">
                    Tampilkan sebagai destinasi unggulan
                </label>
                <button type="submit" class="rounded-lg bg-teal-700 px-5 py-2.5 font-semibold text-white hover:bg-teal-800 md:col-span-2 md:justify-self-start">
                    Simpan destinasi
                </button>
            </form>
        </section>

        <form method="GET" action="{{ route('admin.destinations') }}" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 sm:grid-cols-[1fr_auto_auto]">
            <label class="sr-only" for="destination-search">Cari destinasi</label>
            <input id="destination-search" name="q" value="{{ $filters['q'] ?? '' }}" type="search" placeholder="Cari nama, alamat, atau deskripsi" class="rounded-lg border-slate-300">
            <label class="sr-only" for="destination-category">Kategori</label>
            <select id="destination-category" name="category" class="rounded-lg border-slate-300">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ ucfirst($category) }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button class="rounded-lg bg-slate-800 px-4 py-2 font-semibold text-white" type="submit">Cari</button>
                <a href="{{ route('admin.destinations') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-slate-700">Reset</a>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Foto</th>
                            <th class="px-4 py-3">Destinasi</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Alamat</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($destinations as $destination)
                            <tr class="align-top">
                                <td class="px-4 py-4">
                                    <img src="{{ $destination->image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image_path) : asset('images/3.png') }}"
                                        alt="{{ $destination->name }}" class="h-16 w-24 rounded-lg object-cover" loading="lazy">
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-900">{{ $destination->name }}</p>
                                    @if ($destination->is_featured)
                                        <span class="mt-1 inline-block rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-800">Unggulan</span>
                                    @endif
                                    <details class="mt-2">
                                        <summary class="cursor-pointer text-teal-800">Lihat deskripsi</summary>
                                        <p class="mt-1 max-w-md whitespace-pre-line text-slate-600">{{ $destination->description ?: 'Belum ada deskripsi.' }}</p>
                                    </details>
                                </td>
                                <td class="px-4 py-4 text-slate-700">{{ ucfirst($destination->category) }}</td>
                                <td class="max-w-xs px-4 py-4 text-slate-600">{{ $destination->address }}</td>
                                <td class="px-4 py-4">
                                    <details>
                                        <summary class="cursor-pointer font-semibold text-teal-800">Edit</summary>
                                        <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" enctype="multipart/form-data" class="mt-3 min-w-64 space-y-3 rounded-lg bg-slate-50 p-3">
                                            @csrf
                                            @method('PATCH')
                                            <label class="block text-xs font-medium">Nama
                                                <input name="name" value="{{ $destination->name }}" required maxlength="255" class="mt-1 block w-full rounded border-slate-300 text-sm">
                                            </label>
                                            <label class="block text-xs font-medium">Kategori
                                                <input name="category" value="{{ $destination->category }}" required maxlength="100" class="mt-1 block w-full rounded border-slate-300 text-sm">
                                            </label>
                                            <label class="block text-xs font-medium">Alamat
                                                <input name="address" value="{{ $destination->address }}" required maxlength="1000" class="mt-1 block w-full rounded border-slate-300 text-sm">
                                            </label>
                                            <label class="block text-xs font-medium">Deskripsi
                                                <textarea name="description" rows="3" maxlength="10000" class="mt-1 block w-full rounded border-slate-300 text-sm">{{ $destination->description }}</textarea>
                                            </label>
                                            <label class="block text-xs font-medium">Ganti foto
                                                <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="mt-1 block w-full text-xs">
                                            </label>
                                            <label class="flex items-center gap-2 text-xs">
                                                <input name="is_featured" type="checkbox" value="1" @checked($destination->is_featured) class="rounded border-slate-300 text-teal-700">
                                                Destinasi unggulan
                                            </label>
                                            <button type="submit" class="rounded bg-teal-700 px-3 py-2 text-xs font-semibold text-white">Simpan perubahan</button>
                                        </form>
                                    </details>
                                    <form method="POST" action="{{ route('admin.destinations.destroy', $destination) }}" class="mt-2" onsubmit="return confirm('Hapus destinasi {{ addslashes($destination->name) }}? Destinasi yang terhubung ke paket harus dilepas terlebih dahulu.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-rose-700 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada destinasi yang cocok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3">{{ $destinations->links() }}</div>
        </div>
    </div>
</x-app-layout>
