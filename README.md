# Travel Yuk

Travel Yuk adalah aplikasi pemesanan paket wisata berbasis Laravel, Blade, Tailwind CSS, Alpine.js, dan MySQL. Aplikasi mendukung katalog paket/destinasi, pengelolaan admin, booking dengan reservasi kuota, serta status pembayaran yang dikelola manual.

## Persyaratan

- PHP dan Composer sesuai versi yang tercantum di `composer.json`
- MySQL (misalnya melalui XAMPP/phpMyAdmin)
- Node.js dan npm

## Instalasi lokal (Windows/XAMPP)

Jalankan perintah dari folder project di PowerShell:

```powershell
composer install
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate
```

Atur `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env` agar sesuai dengan MySQL lokal. Buat database yang kosong melalui phpMyAdmin jika belum tersedia. Jangan menimpa `.env` yang sudah berisi konfigurasi lokal.

### Periksa dan jalankan migrasi

Sebelum migrasi pertama kali, buat backup database. Periksa status migrasi:

```powershell
php artisan migrate:status
```

Migrasi katalog dan booking terbaru menambahkan kolom serta tabel `booking_items`; migrasi tersebut tidak menghapus tabel atau data lama. Tetap tinjau file migrasi dan pastikan konfigurasi menunjuk ke database yang benar sebelum menjalankan:

```powershell
php artisan migrate
```

### Aset dan penyimpanan gambar

```powershell
npm.cmd ci
npm.cmd run build
php artisan storage:link
```

Upload destinasi dan paket disimpan pada disk `public`; `storage:link` diperlukan agar gambar yang diunggah dapat ditampilkan.

### Menjalankan aplikasi

```powershell
php artisan serve
```

Buka `http://127.0.0.1:8000`. Untuk perubahan frontend selama pengembangan, jalankan `npm.cmd run dev` di terminal terpisah.

## Pengujian

```powershell
php artisan test
```

## Alur booking dan pembayaran

Pengguna yang masuk memilih paket, mengisi detail pemesanan, lalu menerima kode booking dan status **menunggu / belum dibayar**. Kuota paket dan tiket dipesan dalam transaksi database, dan booking yang dibatalkan mengembalikan kuota. Admin mengelola status booking serta status pembayaran melalui dashboard. Aplikasi belum terhubung ke payment gateway dan tidak meminta detail kartu atau transfer melalui formulir online.
