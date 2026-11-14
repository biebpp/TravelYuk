# Laporan Audit Project Travel Yuk

**Tanggal:** 14 November 2026
**Status:** Perbaikan dan verifikasi selesai untuk defect yang terkonfirmasi; temuan risiko lain tetap dicatat.

## Cakupan pemeriksaan

Pemeriksaan mencakup 53 route terdaftar, 21 controller, model dan service aplikasi, middleware/auth, 63 Blade view, JavaScript/CSS, 15 migration, seeder, dan 90 file PHP di `app`, `routes`, `database`, `tests`, dan `config`.

Database lokal menggunakan MySQL. `php artisan migrate:status` berhasil membaca status dan menunjukkan semua 15 migration sudah diterapkan. Tidak ada migration atau perubahan data yang dijalankan.

## Error yang ditemukan dan diperbaiki

1. **Route status booking admin tidak terdaftar.** View admin menggunakan route `admin.booking.status`, tetapi route PATCH belum tersedia. Route ditambahkan di `routes/web.php`; tes admin memverifikasi halaman dapat dibuka dan status dapat diperbarui.
2. **Struktur dokumen layout tidak valid.** Layout aplikasi membuka `<body>` kedua di dalam body dari komponen head. Tag body bersarang dihapus; class `overflow-hidden` dipertahankan pada body luar.
3. **Konten halaman berada di dalam landmark `<header>`.** Slot halaman dipindahkan ke luar elemen header. Wrapper styling tetap dipertahankan, sehingga perubahan ini memperbaiki struktur semantik tanpa mengubah class tampilan.
4. **Status booking tidak konsisten.** Update sebelumnya mengizinkan booking ditolak sekaligus ditandai lunas dan tetap melepas kuota. Kombinasi tersebut sekarang ditolak sebelum inventaris dilepas; tes memastikan status dan kuota tidak berubah.
5. **Coverage CRUD yang belum ada ditambah.** Tes kini mencakup destination create/update/delete dan upload gambar, bundle update/delete dan perlindungan booking history, serta user management oleh superadmin dan batas akses admin.
6. **Kredensial seeder tetap dihapus.** Akun admin dan superadmin sekarang hanya dapat dibuat memakai konfigurasi `TRAVELYUK_SEED_ADMIN_EMAIL`, `TRAVELYUK_SEED_ADMIN_PASSWORD`, `TRAVELYUK_SEED_SUPERADMIN_EMAIL`, dan `TRAVELYUK_SEED_SUPERADMIN_PASSWORD`. Seeding non-production melewati akun jika semuanya kosong; production menolak konfigurasi kosong/parsial, email invalid, atau email admin/superadmin yang sama. Akun yang sudah ada tidak direset dan role yang bertentangan ditolak.
7. **Perintah tes di README diselaraskan dengan skrip Composer.** Dokumentasi kini memakai `composer test`, yang membersihkan config cache sebelum menjalankan Laravel Test.

## File yang berubah

- `routes/web.php` — mendaftarkan route PATCH status booking admin.
- `app/Http/Controllers/Admin/BookingController.php` — menolak kombinasi ditolak/lunas.
- `resources/views/components/head.blade.php` — menerima class tambahan untuk body luar.
- `resources/views/layouts/app.blade.php` — menghapus body bersarang dan memisahkan konten dari header.
- `tests/Feature/BookingFlowTest.php` — tes route/update, konsistensi inventaris, dan struktur layout.
- `tests/Feature/TravelCatalogTest.php` — tes destination CRUD/upload dan bundle update/delete.
- `tests/Feature/AdminUserManagementTest.php` — tes user CRUD, otorisasi superadmin, dan cascade penghapusan admin.
- `database/seeders/DatabaseSeeder.php`, `AdminSeeder.php`, `SuperadminSeeder.php`, `EnvironmentAccountSeeder.php`, dan `config/seeding.php` — seeding akun privileged berbasis environment.
- `.env.example` dan `README.md` — dokumentasi variabel akun dan langkah seeding tanpa kredensial tetap.
- `tests/Feature/SeederAccountsTest.php` — tes konfigurasi, idempotensi, dan kegagalan aman seeder.
- `docs/full-project-audit.md` — laporan audit.

Perubahan belum dikomit.

## Hasil testing

- `composer test`: **49 tes lulus, 187 assertions**.
- PHP lint: **90 file diperiksa, tanpa syntax error**.
- Laravel Pint (`--test`) pada file PHP/Blade yang berubah: lulus.
- `composer validate --no-check-publish`: valid.
- `npm run build`: berhasil. Vite memberi peringatan bahwa `/images/3.png` tidak dibundel; file tersedia di `public/images` dan berhasil dimuat browser.
- `php artisan route:list`: **53 route** terdaftar, termasuk route status booking admin.
- `php artisan migrate:status`: **15 migration** berstatus sudah diterapkan.
- Browser lokal: 18 URL diperiksa. Halaman publik memberi 200; halaman terlindungi mengarahkan pengunjung tanpa login ke halaman login. Tidak ada console error, request gagal, atau respons gagal pada smoke test.
- Responsive: homepage, login, register, daftar paket, dan detail paket diperiksa pada lebar 390 px tanpa overflow horizontal; menu mobile dapat dibuka. Gambar yang tampil pada homepage berhasil dimuat.
- Log Laravel: ditemukan 64 entri error historis bertanggal 20 Oktober–11 November 2026, termasuk SQLSTATE, MissingAppKey, ViewException, dan ParseError. File log terakhir diperbarui 11 November; error tersebut tidak muncul kembali pada tes atau smoke test saat audit, sehingga tidak dapat dipastikan sebagai error aktif.

## Cakupan fitur dan batas pengujian

Tes otomatis mencakup login/logout, registrasi, reset dan pembaruan password, verifikasi email, profil, destination/ticket CRUD, upload gambar destination dengan file uji, pembuatan dan pembaruan paket serta hubungan tiket/destinasi, pencarian/filter katalog, user management/otorisasi, alur booking/kuota, dan seeder admin/superadmin.

Halaman admin/client yang memerlukan login diuji lewat feature test dengan user test; browser tidak login menggunakan akun seeder. Lookup ke layanan geocoding pihak ketiga dan pengiriman email nyata tidak diuji. Upload diuji memakai storage dan file palsu, bukan melalui filesystem deployment. Route terlindungi diuji untuk redirect login, bukan setiap aksi terautentikasi di browser.

## Temuan yang belum diubah

- **Endpoint geocoding publik belum memiliki throttle dan validasi panjang alamat.** Setiap request valid memanggil layanan Nominatim. Batas request/input dapat mengubah perilaku API; belum diterapkan dan endpoint eksternal belum diuji langsung.
- **Menghapus admin dapat menghapus paket dan riwayat booking terkait.** Foreign key paket ke pemilik admin menggunakan cascade; controller hanya memblokir penghapusan akun yang memiliki booking langsung. Pengguna mengonfirmasi cascade ini dipertahankan; tes memverifikasi perilaku tersebut di database uji, tanpa menghapus data project.
- **Penghapusan destinasi dapat menghapus tiket yang tidak dipakai paket.** Ini mengikuti cascade foreign key; tidak ada data yang dihapus saat audit.
- **Root repository memuat 67 file `.bat` dan 170 file `_dummy*.txt` yang terlacak Git.** Beberapa batch file menghapus file sementara lain dengan wildcard. Tidak ada batch file yang dijalankan dan tidak ada file tersebut yang dihapus.
