"use client";

import Link from "next/link";
import { useEffect, useMemo, useState } from "react";
import {
  demoDesaList,
  demoProyekList,
  demoRingkasan,
  fetchProyekList,
  fetchRingkasan,
  labelKategori,
  labelStatus,
  rp,
} from "../lib/api";
import type { Proyek, Ringkasan } from "../lib/types";

export default function Home() {
  const [list, setList] = useState<Proyek[]>(() => demoProyekList());
  const [ringkasan, setRingkasan] = useState<Ringkasan>(() => demoRingkasan());
  const [q, setQ] = useState("");
  const [desa, setDesa] = useState("");
  const [status, setStatus] = useState("");
  const [kategori, setKategori] = useState("");
  const desaList = demoDesaList();

  useEffect(() => {
    // Kalau NEXT_PUBLIC_API_URL diset, ganti data demo dengan data API Laravel.
    // Kalau tidak diset / API tidak reachable, data demo tetap dipakai.
    fetchProyekList().then(setList);
    fetchRingkasan().then(setRingkasan);
  }, []);

  const filtered = useMemo(() => {
    const query = q.toLowerCase();
    return list.filter((p) => {
      if (query && !p.nama.toLowerCase().includes(query)) return false;
      if (desa && p.desa.id !== Number(desa)) return false;
      if (status === "terlambat" && !p.terlambat) return false;
      if (status && status !== "terlambat" && p.status !== status) return false;
      if (kategori && p.kategori !== kategori) return false;
      return true;
    });
  }, [list, q, desa, status, kategori]);

  return (
    <>
      <section>
        <div className="summary">
          <div className="stat">
            <strong>{ringkasan.totalProyek}</strong>
            <span>proyek demo</span>
          </div>
          <div className="stat">
            <strong>{rp(ringkasan.totalPagu)}</strong>
            <span>total pagu</span>
          </div>
          <div className="stat">
            <strong>{rp(ringkasan.totalCair)}</strong>
            <span>total dana cair</span>
          </div>
          <div className="stat alert">
            <strong>{ringkasan.terlambat} proyek</strong>
            <span>terlambat / deviasi &gt; 10 poin</span>
          </div>
        </div>

        <h2>Skor keterbukaan desa</h2>
        <p className="muted small">
          Skor = kelengkapan dokumen 40% + ketepatan update 30% + respons
          laporan 30%. Lihat PRD untuk rumusnya.
        </p>
        <div className="desa-grid">
          {desaList.map((d) => (
            <div className="desa-card" key={d.id}>
              <div>
                <strong>Desa {d.nama}</strong>
                <span className="muted">, Kec. {d.kecamatan}</span>
              </div>
              <div className="score">
                {d.skor.total}
                <small>/100</small>
              </div>
              <div className="muted small">
                Dokumen {d.skor.dokumen} · Update {d.skor.update} · Respons{" "}
                {d.skor.respons}
              </div>
            </div>
          ))}
        </div>

        <h2>Cari proyek</h2>
        <div className="filters">
          <input
            type="search"
            placeholder="Cari nama proyek, misal: Pengaspalan Jalan X"
            value={q}
            onChange={(e) => setQ(e.target.value)}
          />
          <select value={desa} onChange={(e) => setDesa(e.target.value)}>
            <option value="">Semua desa</option>
            {desaList.map((d) => (
              <option key={d.id} value={d.id}>
                Desa {d.nama}
              </option>
            ))}
          </select>
          <select value={status} onChange={(e) => setStatus(e.target.value)}>
            <option value="">Semua status</option>
            <option value="terlambat">Terlambat</option>
            <option value="tender">Tender</option>
            <option value="kontrak">Kontrak</option>
            <option value="pelaksanaan">Pelaksanaan</option>
            <option value="selesai">Selesai</option>
          </select>
          <select
            value={kategori}
            onChange={(e) => setKategori(e.target.value)}
          >
            <option value="">Semua kategori</option>
            <option value="jalan">Jalan</option>
            <option value="drainase">Drainase</option>
            <option value="air_bersih">Air bersih</option>
            <option value="fasilitas_umum">Fasilitas umum</option>
            <option value="irigasi">Irigasi</option>
          </select>
        </div>
        <p className="muted small">
          {filtered.length} dari {list.length} proyek
        </p>
        <div>
          {filtered.length ? (
            filtered.map((p) => (
              <Link
                key={p.id}
                href={`/proyek/${p.id}`}
                className="card card-link"
              >
                <div className="card-top">
                  <span
                    className={`badge ${p.terlambat ? "late" : p.status}`}
                  >
                    {p.terlambat ? "Terlambat" : labelStatus[p.status]}
                  </span>
                  <span className="muted small">
                    Desa {p.desa.nama} · {labelKategori[p.kategori]}
                  </span>
                </div>
                <h3>{p.nama}</h3>
                <div className="bar">
                  <div style={{ width: `${p.fisik}%` }} />
                </div>
                <div className="row small">
                  <span>
                    Fisik <strong>{p.fisik}%</strong> · Rencana {p.rencana}% ·
                    Deviasi{" "}
                    <strong className={p.deviasi < 0 ? "neg" : "pos"}>
                      {p.deviasi > 0 ? "+" : ""}
                      {p.deviasi} poin
                    </strong>
                  </span>
                </div>
                <div className="row small muted">
                  <span>
                    Pagu {rp(p.pagu)} · Cair {rp(p.cair)}
                  </span>
                </div>
              </Link>
            ))
          ) : (
            <p className="muted">
              Tidak ada proyek yang cocok dengan filter. Coba hapus kata kunci
              atau filter.
            </p>
          )}
        </div>
        <p className="muted small">
          Tips demo: filter Status = Terlambat, lalu klik proyek Pengaspalan
          Jalan Raya Sukamaju untuk melihat milestone yang lewat deadline dan
          kurva rencana vs fisik yang bercabang.
        </p>
      </section>
    </>
  );
}
