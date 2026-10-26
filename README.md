# PantauDesa

Platform pemantauan proyek infrastruktur desa: timeline proyek, anggaran, progres fisik vs rencana, flag keterlambatan otomatis, dan skor keterbukaan desa.

> **Penting:** semua data di repo ini adalah **data demo untuk lomba**, bukan data resmi pemerintah. Lihat `PRD.md` bagian "Aturan data dan kejujuran demo".

## Isi repo
- `PRD.md` — scope MVP, alur demo 3 menit, dan yang sengaja tidak dipalsukan.
- `prototype/` — prototipe web tanpa build-step. Buka `prototype/index.html` di browser, langsung bisa didemo.
- `database/schema.sql` — skema PostgreSQL produksi.
- `database/seed.sql` — 12 proyek demo di 4 desa, data yang sama dengan prototipe.
- `docs/api-spec.md` — endpoint REST gaya Laravel untuk dibawa ke Next.js + Laravel.

## Cara demo tercepat
1. Buka `prototype/index.html`.
2. Coba filter Status = "Terlambat".
3. Klik proyek "Pengaspalan Jalan Raya Sukamaju" — lihat milestone yang lewat deadline, kurva rencana vs fisik, dan anggaran.
4. Klik "Ikuti proyek", lalu kirim laporan pada milestone Pelaksanaan.

## Cara lanjut ke produksi
1. Buat database dari `database/schema.sql`, isi dengan `database/seed.sql` (ganti data demo dengan data riil yang sah sebelum dipakai publik).
2. Implementasikan endpoint di `docs/api-spec.md` dengan Laravel.
3. Ganti `prototype/data.js` dengan fetch ke API tersebut — bentuk objeknya sudah disamakan dengan respons API.
