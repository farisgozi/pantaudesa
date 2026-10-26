# PantauDesa — Backend Laravel

Kode backend lengkap (model, migrasi, controller, route, seeder) untuk API yang
dipakai frontend Next.js di `../frontend`. File-file ini disalin ke proyek
Laravel standar — VM ini tidak memasang PHP/Composer, jadi backend belum bisa
dijalankan di sini, tapi strukturnya mengikuti konvensi Laravel dan bentuk
responsnya sudah disamakan dengan `../docs/api-spec.md`.

> Data seeder adalah **data demo fiktif untuk lomba**, bukan data resmi pemerintah.

## Cara menjalankan (di mesin dengan PHP 8.2+ dan Composer)
```bash
composer create-project laravel/laravel pantaudesa-backend
cd pantaudesa-backend
# Salin isi folder backend/ ini ke proyek tersebut:
#   app/Models/* -> app/Models/
#   app/Http/Controllers/Api/* -> app/Http/Controllers/Api/
#   database/migrations/* -> database/migrations/
#   database/seeders/* -> database/seeders/
#   routes/api.php -> routes/api.php (gabungkan dengan route bawaan bila perlu)
#   PENTING (Laravel 11/12): daftarkan route API di bootstrap/app.php, karena
#   skeleton baru tidak selalu memuatnya secara default:
#     ->withRouting(web: ..., api: __DIR__.'/../routes/api.php', ...)

cp .env.example .env
# Atur DB_DATABASE, DB_USERNAME, DB_PASSWORD di .env (PostgreSQL disarankan)
php artisan key:generate
php artisan migrate --seed
php artisan serve --port=8000
```

## Verifikasi lokal yang sudah dilakukan di VM ini (27 Okt 2026)
- PHP 8.3.6 + Composer 2.10.3 dipasang lewat apt dan installer resmi Composer.
- Proyek Laravel dibuat di `../laravel-app` (instance jalan, tidak ikut zip portable),
  memakai SQLite supaya tidak perlu server PostgreSQL untuk demo.
- `php artisan migrate:fresh --seed`: 12 proyek, 4 desa, total pagu Rp 4.650.000.000.
- 6 route API terdaftar dan teruji: ringkasan, daftar, detail, filter terlambat,
  pencarian case-insensitive, POST laporan, POST ikuti, skor desa.
- Tiga bug yang ditemukan dan diperbaiki saat verifikasi ini:
  1. Route API tidak termuat di skeleton Laravel 11/12 tanpa pendaftaran eksplisit
     di `bootstrap/app.php` (lihat catatan PENTING di atas).
  2. Model `Desa`/`Proyek` butuh `$table` eksplisit karena nama tabel Indonesia
     tidak dijamak otomatis oleh Laravel.
  3. Pencarian memakai `ilike` (PostgreSQL saja) dan `Stringable` untuk perbandingan
     status — diganti ke `LOWER(nama) LIKE` dan `input()` string biasa.
- Data uji POST (satu laporan dan satu ikuti) sudah dihapus lagi setelah pengujian,
  jadi database kembali ke kondisi seed.

## Cara menghubungkan frontend
```bash
cd ../frontend
NEXT_PUBLIC_API_URL=http://localhost:8000 npm run dev
```
Tanpa `NEXT_PUBLIC_API_URL`, frontend memakai data demo lokal yang angkanya
sama dengan seeder, jadi demo tetap jalan walau backend belum aktif.

## Endpoint
- `GET /api/v1/ringkasan`
- `GET /api/v1/proyek?q=&desa_id=&status=&kategori=`
- `GET /api/v1/proyek/{id}`
- `POST /api/v1/proyek/{id}/ikuti` — body: `{"pengguna_id": 1}` (MVP demo; ganti ke Sanctum untuk produksi)
- `POST /api/v1/proyek/{id}/laporan` — body: `pelapor_nama`, `isi`, `milestone_id` atau `milestone_judul`
- `GET /api/v1/desa/{id}/skor`

## Catatan keamanan untuk produksi (bukan MVP lomba)
- Lindungi endpoint tulis dengan Sanctum; jangan terima `pengguna_id` dari body.
- Validasi upload foto progres (tipe, ukuran, geotag) di sisi server.
- Ganti data demo dengan data riil yang sah sebelum dipakai publik.
