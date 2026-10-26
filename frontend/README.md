# PantauDesa — Frontend Next.js

Port dari prototipe HTML di `../prototype` ke Next.js 14 (App Router, TypeScript).
Tanpa backend, aplikasi memakai data demo lokal yang sama dengan seeder Laravel.
Dengan backend, set `NEXT_PUBLIC_API_URL` dan semua halaman memakai API.

## Menjalankan
```bash
npm install
npm run dev        # http://localhost:3000
npm run build      # verifikasi produksi
npm start
```

## Menghubungkan ke backend Laravel
```bash
NEXT_PUBLIC_API_URL=http://localhost:8000 npm run dev
```
Lihat `../backend/README.md` untuk cara menjalankan API-nya.

## Struktur
- `app/page.tsx` — beranda: ringkasan, skor desa, pencarian + filter proyek.
- `app/proyek/[id]/page.tsx` — detail: anggaran, kurva rencana vs fisik, timeline, dokumen, laporan, ikuti proyek.
- `lib/api.ts` — client API + fallback data demo + rumus flag terlambat (sama dengan backend).
- `lib/demo-data.ts` — data demo, digenerate dari `../prototype/data.js` (jangan edit manual; edit prototipe lalu regenerate).
- `app/globals.css` — gaya dari prototipe, mobile-first.

## Catatan
- Data di halaman ditandai sebagai data demo fiktif, bukan data resmi pemerintah.
- Sebelum submission final, perbarui Next.js ke versi patch terbaru (`npm audit` / `npm update next`) — pin di `package.json` adalah versi yang terverifikasi build di VM ini.
