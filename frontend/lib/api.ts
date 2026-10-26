import { BULAN, DESA, PROYEK } from "./demo-data";
import type { Desa, Laporan, Proyek, Ringkasan } from "./types";

// Bentuk data demo di demo-data.ts sama dengan prototipe HTML.
// normalize() mengubahnya ke bentuk respons API Laravel (lihat backend/routes/api.php),
// supaya frontend tidak perlu berubah saat backend sudah jalan.
export const API_URL = process.env.NEXT_PUBLIC_API_URL || "";

type DemoProyek = (typeof PROYEK)[number];

export function normalize(p: DemoProyek): Proyek {
  const desa = DESA.find((d) => d.id === p.desaId) as unknown as Desa;
  const terlambat =
    p.status === "terlambat" ||
    p.rencana - p.fisik > 10 ||
    p.milestones.some((m) => m.status === "terlambat");
  return {
    id: p.id,
    desa: { ...desa, skor: (desa as unknown as { skor: Desa["skor"] }).skor },
    nama: p.nama,
    kategori: p.kategori,
    pagu: p.pagu,
    kontrak: p.kontrak,
    cair: p.cair,
    mulai: p.mulai,
    target: p.target,
    fisik: p.fisik,
    rencana: p.rencana,
    deviasi: p.fisik - p.rencana,
    terlambat,
    status: p.status,
    deskripsi: p.deskripsi,
    milestones: p.milestones as unknown as Proyek["milestones"],
    kurvaRencana: p.kurvaRencana,
    kurvaFisik: p.kurvaFisik,
    dokumen: p.dokumen,
    laporan: p.laporan as Laporan[],
  };
}

export function demoProyekList(): Proyek[] {
  return PROYEK.map(normalize);
}

export function demoDesaList(): Desa[] {
  return DESA as unknown as Desa[];
}

export function demoRingkasan(): Ringkasan {
  const list = demoProyekList();
  return {
    totalProyek: list.length,
    totalPagu: list.reduce((a, p) => a + p.pagu, 0),
    totalCair: list.reduce((a, p) => a + p.cair, 0),
    terlambat: list.filter((p) => p.terlambat).length,
  };
}

export function demoBulan(): string[] {
  return BULAN;
}

async function apiGet<T>(path: string): Promise<T | null> {
  if (!API_URL) return null;
  try {
    const res = await fetch(`${API_URL}${path}`, { cache: "no-store" });
    if (!res.ok) return null;
    return (await res.json()) as T;
  } catch {
    return null;
  }
}

export async function fetchProyekList(): Promise<Proyek[]> {
  const data = await apiGet<Proyek[]>("/api/v1/proyek");
  return data ?? demoProyekList();
}

export async function fetchProyekDetail(id: number): Promise<Proyek | null> {
  const data = await apiGet<Proyek>(`/api/v1/proyek/${id}`);
  if (data) return data;
  return demoProyekList().find((p) => p.id === id) ?? null;
}

export async function fetchRingkasan(): Promise<Ringkasan> {
  const data = await apiGet<Ringkasan>("/api/v1/ringkasan");
  return data ?? demoRingkasan();
}

export async function kirimLaporan(
  proyekId: number,
  laporan: { milestone: string; nama: string; isi: string }
): Promise<boolean> {
  if (!API_URL) return true; // Mode demo: laporan disimpan lokal di halaman detail.
  try {
    const res = await fetch(`${API_URL}/api/v1/proyek/${proyekId}/laporan`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        pelapor_nama: laporan.nama,
        isi: laporan.isi,
        milestone_judul: laporan.milestone,
      }),
    });
    return res.ok;
  } catch {
    return false;
  }
}

export const rp = (n: number | null) =>
  n == null ? "—" : "Rp " + n.toLocaleString("id-ID");

export const labelStatus: Record<string, string> = {
  tender: "Tender",
  kontrak: "Kontrak",
  pelaksanaan: "Pelaksanaan",
  selesai: "Selesai",
  terlambat: "Terlambat",
};

export const labelKategori: Record<string, string> = {
  jalan: "Jalan",
  drainase: "Drainase",
  air_bersih: "Air bersih",
  fasilitas_umum: "Fasilitas umum",
  irigasi: "Irigasi",
};

export const labelMilestone: Record<string, string> = {
  akan_datang: "Akan datang",
  selesai: "Selesai",
  terlambat: "Terlambat",
  tepat_waktu: "Tepat waktu",
};
