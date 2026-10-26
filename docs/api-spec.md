# PantauDesa — API Spec (gaya Laravel REST)

Base URL produksi: `/api/v1`. Prototipe di `prototype/data.js` memakai bentuk respons yang sama, supaya migrasi ke API tinggal mengganti sumber data.

## Ringkasan
- `GET /api/v1/ringkasan` — total proyek, total pagu, total cair, jumlah terlambat, skor keterbukaan per desa.
- `GET /api/v1/proyek?q=&desa_id=&status=&kategori=` — daftar proyek + deviasi + flag terlambat.
- `GET /api/v1/proyek/{id}` — detail proyek + milestone + progres bulanan + dokumen + laporan.
- `POST /api/v1/proyek/{id}/ikuti` — warga mengikuti proyek (auth warga).
- `POST /api/v1/proyek/{id}/laporan` — body: `milestone_id` (opsional), `isi`. Status awal selalu `diterima`.
- `GET /api/v1/desa/{id}/skor` — komponen skor: kelengkapan dokumen 40%, ketepatan update 30%, respons laporan 30%.

## Contoh respons `GET /api/v1/proyek/{id}` (disederhanakan)
```json
{
  "id": 1,
  "nama": "Pengaspalan Jalan Raya Sukamaju",
  "desa": { "id": 1, "nama": "Sukamaju", "kecamatan": "Cibinong" },
  "kategori": "jalan",
  "pagu": 450000000,
  "nilai_kontrak": 425000000,
  "dana_cair": 255000000,
  "progres_fisik": 42,
  "progres_rencana": 60,
  "deviasi": -18,
  "terlambat": true,
  "milestone": [
    { "tahap": "pelaksanaan", "judul": "Pengaspalan lapis 1", "tanggal_rencana": "2026-09-20", "tanggal_realisasi": null, "status": "terlambat" }
  ]
}
```

## Aturan flag terlambat (di backend, bukan di frontend)
Proyek ditandai terlambat bila salah satu terpenuhi:
1. Ada milestone dengan `tanggal_realisasi IS NULL` dan `tanggal_rencana < CURRENT_DATE`.
2. `(progres_rencana - progres_fisik) > 10`.

Frontend hanya menampilkan flag dari API; prototipe menghitung dengan rumus yang sama di `prototype/app.js` agar demonya konsisten.
