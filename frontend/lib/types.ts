export type Desa = {
  id: number;
  nama: string;
  kecamatan: string;
  kabupaten: string;
  skor: { dokumen: number; update: number; respons: number; total: number };
};

export type Milestone = {
  tahap: string;
  judul: string;
  rencana: string;
  realisasi: string | null;
  status: "akan_datang" | "selesai" | "terlambat" | "tepat_waktu";
  catatan?: string;
};

export type Dokumen = { jenis: string; tersedia: boolean };

export type Laporan = {
  nama: string;
  isi: string;
  milestone: string;
  status: string;
  tanggapan: string;
};

export type Proyek = {
  id: number;
  desa: Desa;
  nama: string;
  kategori: string;
  pagu: number;
  kontrak: number | null;
  cair: number;
  mulai: string | null;
  target: string;
  fisik: number;
  rencana: number;
  deviasi: number;
  terlambat: boolean;
  status: string;
  deskripsi: string;
  milestones: Milestone[];
  kurvaRencana: number[];
  kurvaFisik: number[];
  dokumen: Dokumen[];
  laporan: Laporan[];
};

export type Ringkasan = {
  totalProyek: number;
  totalPagu: number;
  totalCair: number;
  terlambat: number;
};
