import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "PantauDesa — Lacak Proyek & Anggaran Desa",
  description:
    "Cari nama proyek, lihat anggarannya sudah cair berapa, progres fisiknya tertinggal atau tidak, dan bukti milestone-nya.",
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="id">
      <body>
        <header>
          <h1>PantauDesa</h1>
          <p>
            Cari nama proyek, lihat anggarannya sudah cair berapa, progres
            fisiknya tertinggal atau tidak, dan bukti milestone-nya — dalam
            satu halaman.
          </p>
        </header>
        <div className="demo">
          ⚠️ Data demo untuk lomba — nama desa, proyek, dan angka di halaman
          ini fiktif dan realistis, bukan data resmi pemerintah.
        </div>
        <main>{children}</main>
      </body>
    </html>
  );
}
