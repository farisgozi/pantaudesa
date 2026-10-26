# PantauDesa — PRD MVP

## Nama dan janji produk
**PantauDesa**: warga bisa melacak satu proyek infrastruktur desa dari tender sampai serah terima, lengkap dengan anggaran dan bukti progres — bukan sekadar laporan jalan rusak.

Kalimat demo 10 detik: "Cari nama proyek, lihat anggarannya sudah cair berapa, progres fisiknya tertinggal atau tidak, dan foto buktinya."

## Masalah
Warga tidak tahu kapan jalan diperbaiki dan ke mana larinya anggaran. Informasi tersebar di papan pengumuman, grup WhatsApp, dan media sosial, tanpa satu timeline resmi yang bisa dicek ulang.

## Pengguna
1. **Warga** — mencari proyek, mengikuti proyek, melapor kendala pada milestone tertentu.
2. **Admin desa / operator** — menginput proyek, milestone, realisasi anggaran, dan foto progres.
3. **Perangkat desa / pemeriksa (read-only)** — melihat skor keterbukaan dan daftar proyek terlambat.

## Scope MVP (yang dibangun di prototipe ini)
- [x] Daftar proyek dengan pencarian nama dan filter desa, status, kategori.
- [x] Detail proyek: pagu, nilai kontrak, dana cair, sisa anggaran.
- [x] Timeline milestone: Tender → Kontrak → Pencairan → Pelaksanaan → Serah terima, masing-masing dengan tanggal rencana, tanggal realisasi, dan status.
- [x] Kurva S sederhana: progres rencana vs progres fisik per bulan.
- [x] Flag keterlambatan otomatis: milestone lewat deadline dan belum selesai, atau deviasi progres fisik vs rencana > 10 poin.
- [x] Skor keterbukaan desa: kelengkapan dokumen 40% + ketepatan update 30% + respons laporan 30%.
- [x] Ikuti proyek (disimpan lokal di browser pada prototipe; di produksi jadi tabel `ikuti_proyek`).
- [x] Lapor kendala yang menempel ke milestone tertentu, lengkap dengan status tindak lanjut.

## Di luar MVP (diceritakan ke juri, tidak dipalsukan di demo)
- Login admin desa dan verifikasi dokumen resmi.
- Integrasi data SIRUP/LPSE dan SIPD — di demo, data ditandai jelas sebagai **data demo**, bukan data resmi pemerintah.
- Notifikasi push/email saat milestone berubah.
- Peta proyek berbasis koordinat desa (MVP memakai filter desa, bukan titik GPS palsu).

## Aturan data dan kejujuran demo
- Semua nama desa, proyek, dan angka di `database/seed.sql` dan `prototype/data.js` adalah **data demo fiktif yang realistis**, dibuat untuk keperluan lomba.
- Jangan pernah mengklaim angka demo sebagai realisasi APBDes resmi.
- Dokumen yang ditampilkan di MVP adalah checklist kelengkapan (ada/tidak), bukan dokumen asli yang diunggah.
- Foto progres di MVP adalah placeholder berlabel, bukan foto proyek nyata.

## Alur demo 3 menit (untuk juri)
1. Beranda: tunjukkan ringkasan — total pagu, jumlah proyek terlambat, dan skor keterbukaan per desa.
2. Filter "Terlambat": buka satu proyek terlambat, tunjukkan milestone mana yang lewat deadline dan deviasinya.
3. Buka tab Anggaran: pagu → kontrak → cair → sisa, dalam satu bar.
4. Buka tab Kurva: garis rencana vs fisik yang mulai bercabang — inilah bukti keterlambatan, bukan opini.
5. Ikuti proyek, lalu kirim laporan kendala pada milestone Pelaksanaan. Laporan langsung muncul dengan status "Diterima".

## Kriteria sukses demo
- Juri bisa menemukan satu proyek dalam < 30 detik lewat pencarian.
- Juri bisa menjawab dalam 1 menit: berapa pagu, berapa cair, progres berapa persen, dan apa yang terlambat.
- Tidak ada satu pun angka yang diklaim sebagai data resmi.

## Stack produksi yang disarankan
- Backend: Laravel (API REST, lihat `docs/api-spec.md`).
- Frontend: Next.js + Tailwind CSS, mobile-first.
- Database: PostgreSQL (skema di `database/schema.sql`).
- Prototipe di folder `prototype/` adalah versi tanpa build-step supaya bisa langsung didemo dari file HTML, dengan struktur data yang sama dengan API produksi.
