"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import {
  demoBulan,
  demoProyekList,
  fetchProyekDetail,
  kirimLaporan,
  labelKategori,
  labelMilestone,
  labelStatus,
  rp,
} from "../../../lib/api";
import type { Proyek } from "../../../lib/types";

function Curve({ p }: { p: Proyek }) {
  const W = 560;
  const H = 220;
  const pad = 30;
  const x = (i: number) => pad + (i * (W - pad * 2)) / 5;
  const y = (v: number) => H - pad - (v / 100) * (H - pad * 2);
  const pts = (arr: number[]) =>
    arr.map((v, i) => `${x(i)},${y(v)}`).join(" ");
  const bulan = demoBulan();
  return (
    <>
      <svg
        viewBox={`0 0 ${W} ${H}`}
        className="curve"
        role="img"
        aria-label="Kurva rencana vs fisik"
      >
        <line x1={pad} y1={pad} x2={pad} y2={H - pad} />
        <line x1={pad} y1={H - pad} x2={W - pad} y2={H - pad} />
        <polyline points={pts(p.kurvaRencana)} className="line-rencana" />
        <polyline points={pts(p.kurvaFisik)} className="line-fisik" />
        {p.kurvaRencana.map((v, i) => (
          <circle key={`r${i}`} className="dot-rencana" cx={x(i)} cy={y(v)} r={4} />
        ))}
        {p.kurvaFisik.map((v, i) => (
          <circle key={`f${i}`} className="dot-fisik" cx={x(i)} cy={y(v)} r={4} />
        ))}
        {bulan.map((b, i) => (
          <text key={b} x={x(i)} y={H - 8} textAnchor="middle">
            {b}
          </text>
        ))}
      </svg>
      <div className="legend">
        <span className="dashed">– – Rencana</span>
        <span className="solid">— Fisik</span>
      </div>
    </>
  );
}

export default function Detail({ params }: { params: { id: string } }) {
  const id = Number(params.id);
  const [p, setP] = useState<Proyek | null>(
    () => demoProyekList().find((x) => x.id === id) ?? null
  );
  const [followed, setFollowed] = useState(false);
  const [milestone, setMilestone] = useState("");
  const [nama, setNama] = useState("");
  const [isi, setIsi] = useState("");
  const [pesan, setPesan] = useState("");

  useEffect(() => {
    fetchProyekDetail(id).then((d) => {
      if (d) setP(d);
    });
    try {
      const saved = JSON.parse(
        localStorage.getItem("pantaudesa_follow") || "[]"
      ) as number[];
      setFollowed(saved.includes(id));
    } catch {
      setFollowed(false);
    }
  }, [id]);

  useEffect(() => {
    if (p && !milestone) setMilestone(p.milestones[0]?.judul ?? "");
  }, [p, milestone]);

  if (!p) return <p>Proyek tidak ditemukan.</p>;

  const sisa = p.pagu - p.cair;
  const persenCair = p.pagu ? Math.round((p.cair / p.pagu) * 100) : 0;

  const toggleFollow = () => {
    try {
      const saved = JSON.parse(
        localStorage.getItem("pantaudesa_follow") || "[]"
      ) as number[];
      const next = followed
        ? saved.filter((x) => x !== id)
        : [...saved, id];
      localStorage.setItem("pantaudesa_follow", JSON.stringify(next));
      setFollowed(!followed);
    } catch {
      setFollowed(!followed);
    }
  };

  const submitLaporan = async (e: React.FormEvent) => {
    e.preventDefault();
    const ok = await kirimLaporan(id, { milestone, nama: nama.trim(), isi: isi.trim() });
    if (!ok) {
      setPesan("Gagal mengirim ke API. Coba lagi.");
      return;
    }
    // Mode demo (tanpa API): tampilkan laporan langsung di halaman.
    if (!process.env.NEXT_PUBLIC_API_URL) {
      setP({
        ...p,
        laporan: [
          { nama: nama.trim(), isi: isi.trim(), milestone, status: "diterima", tanggapan: "" },
          ...p.laporan,
        ],
      });
    } else {
      const d = await fetchProyekDetail(id);
      if (d) setP(d);
    }
    setNama("");
    setIsi("");
    setPesan("Laporan terkirim dan berstatus Diterima. Terima kasih.");
  };

  return (
    <section>
      <Link href="/" className="link">
        ← Kembali ke daftar
      </Link>
      <div className="card-top">
        <span className={`badge ${p.terlambat ? "late" : p.status}`}>
          {p.terlambat ? "Terlambat" : labelStatus[p.status]}
        </span>
        <span className="muted">
          Desa {p.desa.nama}, Kec. {p.desa.kecamatan} ·{" "}
          {labelKategori[p.kategori]}
        </span>
      </div>
      <h2>{p.nama}</h2>
      <p>{p.deskripsi}</p>
      <button className="btn" onClick={toggleFollow}>
        {followed ? "★ Berhenti mengikuti" : "☆ Ikuti proyek ini"}
      </button>
      {followed && (
        <p className="muted small">
          Kamu akan mendapat pembaruan milestone proyek ini (simulasi di
          prototipe).
        </p>
      )}

      <h3>Anggaran</h3>
      <div className="money">
        <div>
          <span>Pagu</span>
          <strong>{rp(p.pagu)}</strong>
        </div>
        <div>
          <span>Nilai kontrak</span>
          <strong>{rp(p.kontrak)}</strong>
        </div>
        <div>
          <span>Dana cair</span>
          <strong>{rp(p.cair)}</strong>
        </div>
        <div>
          <span>Sisa pagu</span>
          <strong>{rp(sisa)}</strong>
        </div>
      </div>
      <div className="bar big">
        <div style={{ width: `${persenCair}%` }} />
      </div>
      <p className="small muted">{persenCair}% dari pagu sudah cair.</p>

      <h3>Progres fisik vs rencana</h3>
      <p>
        Fisik <strong>{p.fisik}%</strong> · Rencana{" "}
        <strong>{p.rencana}%</strong> · Deviasi{" "}
        <strong className={p.deviasi < 0 ? "neg" : "pos"}>
          {p.deviasi} poin
        </strong>{" "}
        {p.terlambat ? "— proyek ini ditandai terlambat." : ""}
      </p>
      <Curve p={p} />

      <h3>Timeline milestone</h3>
      <ol className="timeline">
        {p.milestones.map((m, i) => (
          <li key={i} className={m.status}>
            <strong>{m.judul}</strong>
            <div className="small muted">
              Rencana: {m.rencana} · Realisasi: {m.realisasi || "—"} ·{" "}
              {labelMilestone[m.status]}
            </div>
            {m.catatan && <div className="small">{m.catatan}</div>}
          </li>
        ))}
      </ol>

      <h3>Dokumen (checklist kelengkapan)</h3>
      <ul>
        {p.dokumen.map((d, i) => (
          <li key={i}>
            {d.tersedia ? "✓" : "○"} {d.jenis} —{" "}
            {d.tersedia ? "tersedia" : "belum tersedia"}
          </li>
        ))}
      </ul>

      <h3>Foto progres (placeholder demo)</h3>
      <div className="photos">
        <div className="photo">Ilustrasi: kondisi awal</div>
        <div className="photo">Ilustrasi: progres {p.fisik}%</div>
        <div className="photo">Ilustrasi: target selesai</div>
      </div>
      <p className="small muted">
        Di produksi, foto diunggah operator dengan geotag dan stempel waktu,
        bukan placeholder.
      </p>

      <h3>Laporan warga pada proyek ini</h3>
      {p.laporan.length ? (
        p.laporan.map((l, i) => (
          <div className="laporan" key={i}>
            <strong>{l.nama}</strong>{" "}
            <span className="muted small">
              · {l.milestone} · {l.status}
            </span>
            <p>{l.isi}</p>
            {l.tanggapan && (
              <p className="small">
                <em>Tanggapan:</em> {l.tanggapan}
              </p>
            )}
          </div>
        ))
      ) : (
        <p className="muted small">Belum ada laporan.</p>
      )}

      <form className="form" onSubmit={submitLaporan}>
        <label>
          Milestone terkait
          <select
            value={milestone}
            onChange={(e) => setMilestone(e.target.value)}
          >
            {p.milestones.map((m) => (
              <option key={m.judul}>{m.judul}</option>
            ))}
          </select>
        </label>
        <label>
          Nama pelapor
          <input
            required
            value={nama}
            onChange={(e) => setNama(e.target.value)}
            placeholder="cth: Warga RT 02"
          />
        </label>
        <label>
          Kendala yang ditemukan
          <textarea
            required
            value={isi}
            onChange={(e) => setIsi(e.target.value)}
            placeholder="Tulis kendala yang kamu lihat di lapangan"
          />
        </label>
        <button className="btn" type="submit">
          Kirim laporan
        </button>
        {pesan && <p className="small">{pesan}</p>}
        <p className="small muted">
          Laporan masuk dengan status &quot;Diterima&quot; dan tidak langsung
          mengubah status proyek. Verifikasi dilakukan operator desa.
        </p>
      </form>
    </section>
  );
}
