// PantauDesa — prototipe tanpa build-step. Data dari data.js (data demo).
const $ = (s) => document.querySelector(s);
const rp = (n) => n == null ? "—" : "Rp " + n.toLocaleString("id-ID");
const desaById = (id) => DESA.find((d) => d.id === id);
const deviasi = (p) => p.fisik - p.rencana;
const isTerlambat = (p) => p.status === "terlambat" || (p.rencana - p.fisik) > 10 || p.milestones.some((m) => m.status === "terlambat");
const labelStatus = { tender: "Tender", kontrak: "Kontrak", pelaksanaan: "Pelaksanaan", selesai: "Selesai", terlambat: "Terlambat" };
const labelMilestone = { akan_datang: "Akan datang", selesai: "Selesai", terlambat: "Terlambat", tepat_waktu: "Tepat waktu" };
const labelKategori = { jalan: "Jalan", drainase: "Drainase", air_bersih: "Air bersih", fasilitas_umum: "Fasilitas umum", irigasi: "Irigasi" };

let followed = new Set(JSON.parse(localStorage.getItem("pantaudesa_follow") || "[]"));
let currentId = null;

function saveFollow() { localStorage.setItem("pantaudesa_follow", JSON.stringify([...followed])); }

function renderSummary() {
  const totalPagu = PROYEK.reduce((a, p) => a + p.pagu, 0);
  const totalCair = PROYEK.reduce((a, p) => a + p.cair, 0);
  const telat = PROYEK.filter(isTerlambat).length;
  $("#summary").innerHTML = `
    <div class="stat"><strong>${PROYEK.length}</strong><span>proyek demo</span></div>
    <div class="stat"><strong>${rp(totalPagu)}</strong><span>total pagu</span></div>
    <div class="stat"><strong>${rp(totalCair)}</strong><span>total dana cair</span></div>
    <div class="stat alert"><strong>${telat} proyek</strong><span>terlambat / deviasi &gt; 10 poin</span></div>`;
  $("#desaScores").innerHTML = DESA.map((d) => `
    <div class="desa-card"><div><strong>Desa ${d.nama}</strong><span class="muted">, Kec. ${d.kecamatan}</span></div>
    <div class="score">${d.skor.total}<small>/100</small></div>
    <div class="muted small">Dokumen ${d.skor.dokumen} · Update ${d.skor.update} · Respons ${d.skor.respons}</div></div>`).join("");
}

function filtered() {
  const q = ($("#q").value || "").toLowerCase();
  const desa = $("#fDesa").value, status = $("#fStatus").value, kat = $("#fKategori").value;
  return PROYEK.filter((p) => {
    if (q && !p.nama.toLowerCase().includes(q)) return false;
    if (desa && p.desaId !== Number(desa)) return false;
    if (status === "terlambat" && !isTerlambat(p)) return false;
    if (status && status !== "terlambat" && p.status !== status) return false;
    if (kat && p.kategori !== kat) return false;
    return true;
  });
}

function renderList() {
  const list = filtered();
  $("#count").textContent = `${list.length} dari ${PROYEK.length} proyek`;
  $("#list").innerHTML = list.length ? list.map((p) => {
    const d = desaById(p.desaId), dev = deviasi(p), telat = isTerlambat(p);
    return `<article class="card" onclick="openDetail(${p.id})">
      <div class="card-top"><span class="badge ${telat ? "late" : p.status}">${telat ? "Terlambat" : labelStatus[p.status]}</span>
      <span class="muted small">Desa ${d.nama} · ${labelKategori[p.kategori]}</span></div>
      <h3>${p.nama}</h3>
      <div class="bar"><div style="width:${p.fisik}%"></div></div>
      <div class="row small"><span>Fisik <strong>${p.fisik}%</strong> · Rencana ${p.rencana}% · Deviasi <strong class="${dev < 0 ? "neg" : "pos"}">${dev > 0 ? "+" : ""}${dev} poin</strong></span></div>
      <div class="row small muted"><span>Pagu ${rp(p.pagu)} · Cair ${rp(p.cair)}</span><span>${followed.has(p.id) ? "★ Diikuti" : ""}</span></div>
    </article>`;
  }).join("") : `<p class="muted">Tidak ada proyek yang cocok dengan filter. Coba hapus kata kunci atau filter.</p>`;
}

function curveSVG(p) {
  const W = 560, H = 220, pad = 30;
  const pt = (arr) => arr.map((v, i) => `${pad + i * (W - pad * 2) / 5},${H - pad - (v / 100) * (H - pad * 2)}`).join(" ");
  const dots = (arr, cls) => arr.map((v, i) => `<circle class="${cls}" cx="${pad + i * (W - pad * 2) / 5}" cy="${H - pad - (v / 100) * (H - pad * 2)}" r="4"/>`).join("");
  const labels = BULAN.map((b, i) => `<text x="${pad + i * (W - pad * 2) / 5}" y="${H - 8}" text-anchor="middle">${b}</text>`).join("");
  return `<svg viewBox="0 0 ${W} ${H}" class="curve" role="img" aria-label="Kurva rencana vs fisik">
    <line x1="${pad}" y1="${pad}" x2="${pad}" y2="${H - pad}" /><line x1="${pad}" y1="${H - pad}" x2="${W - pad}" y2="${H - pad}" />
    <polyline points="${pt(p.kurvaRencana)}" class="line-rencana" /><polyline points="${pt(p.kurvaFisik)}" class="line-fisik" />
    ${dots(p.kurvaRencana, "dot-rencana")}${dots(p.kurvaFisik, "dot-fisik")}${labels}</svg>
    <div class="legend"><span class="dashed">– – Rencana</span><span class="solid">— Fisik</span></div>`;
}

function openDetail(id) {
  currentId = id;
  const p = PROYEK.find((x) => x.id === id), d = desaById(p.desaId), telat = isTerlambat(p);
  $("#listView").hidden = true; $("#detailView").hidden = false;
  const sisa = p.pagu - p.cair;
  $("#detail").innerHTML = `
    <button class="link" onclick="backToList()">← Kembali ke daftar</button>
    <div class="card-top"><span class="badge ${telat ? "late" : p.status}">${telat ? "Terlambat" : labelStatus[p.status]}</span>
    <span class="muted">Desa ${d.nama}, Kec. ${d.kecamatan} · ${labelKategori[p.kategori]}</span></div>
    <h2>${p.nama}</h2><p>${p.deskripsi}</p>
    <button class="btn" onclick="toggleFollow(${p.id})">${followed.has(p.id) ? "★ Berhenti mengikuti" : "☆ Ikuti proyek ini"}</button>
    <p class="muted small">${followed.has(p.id) ? "Kamu akan mendapat pembaruan milestone proyek ini (simulasi di prototipe)." : ""}</p>
    <h3>Anggaran</h3>
    <div class="money"><div><span>Pagu</span><strong>${rp(p.pagu)}</strong></div><div><span>Nilai kontrak</span><strong>${rp(p.kontrak)}</strong></div><div><span>Dana cair</span><strong>${rp(p.cair)}</strong></div><div><span>Sisa pagu</span><strong>${rp(sisa)}</strong></div></div>
    <div class="bar big"><div style="width:${p.pagu ? Math.round(p.cair / p.pagu * 100) : 0}%"></div></div>
    <p class="small muted">${p.pagu ? Math.round(p.cair / p.pagu * 100) : 0}% dari pagu sudah cair.</p>
    <h3>Progres fisik vs rencana</h3>
    <p>Fisik <strong>${p.fisik}%</strong> · Rencana <strong>${p.rencana}%</strong> · Deviasi <strong class="${deviasi(p) < 0 ? "neg" : "pos"}">${deviasi(p)} poin</strong> ${telat ? "— proyek ini ditandai terlambat." : ""}</p>
    ${curveSVG(p)}
    <h3>Timeline milestone</h3>
    <ol class="timeline">${p.milestones.map((m) => `<li class="${m.status}"><strong>${m.judul}</strong>
      <div class="small muted">Rencana: ${m.rencana} · Realisasi: ${m.realisasi || "—"} · ${labelMilestone[m.status]}</div>
      ${m.catatan ? `<div class="small">${m.catatan}</div>` : ""}</li>`).join("")}</ol>
    <h3>Dokumen (checklist kelengkapan)</h3>
    <ul>${p.dokumen.map((x) => `<li>${x.tersedia ? "✓" : "○"} ${x.jenis} — ${x.tersedia ? "tersedia" : "belum tersedia"}</li>`).join("")}</ul>
    <h3>Foto progres (placeholder demo)</h3>
    <div class="photos"><div class="photo">Ilustrasi: kondisi awal</div><div class="photo">Ilustrasi: progres ${p.fisik}%</div><div class="photo">Ilustrasi: target selesai</div></div>
    <p class="small muted">Di produksi, foto diunggah operator dengan geotag dan stempel waktu, bukan placeholder.</p>
    <h3>Laporan warga pada proyek ini</h3>
    <div id="laporanList">${p.laporan.length ? p.laporan.map((l) => `<div class="laporan"><strong>${l.nama}</strong> <span class="muted small">· ${l.milestone} · ${l.status}</span><p>${l.isi}</p>${l.tanggapan ? `<p class="small"><em>Tanggapan:</em> ${l.tanggapan}</p>` : ""}</div>`).join("") : `<p class="muted small">Belum ada laporan.</p>`}</div>
    <form id="laporanForm" class="form">
      <label>Milestone terkait<select id="lapMilestone">${p.milestones.map((m) => `<option>${m.judul}</option>`).join("")}</select></label>
      <label>Nama pelapor<input id="lapNama" required placeholder="cth: Warga RT 02"></label>
      <label>Kendala yang ditemukan<textarea id="lapIsi" required placeholder="Tulis kendala yang kamu lihat di lapangan"></textarea></label>
      <button class="btn" type="submit">Kirim laporan</button>
      <p class="small muted">Laporan masuk dengan status "Diterima" dan tidak langsung mengubah status proyek. Verifikasi dilakukan operator desa.</p>
    </form>`;
  $("#laporanForm").addEventListener("submit", (e) => {
    e.preventDefault();
    p.laporan.unshift({ nama: $("#lapNama").value.trim(), isi: $("#lapIsi").value.trim(), milestone: $("#lapMilestone").value, status: "diterima", tanggapan: "" });
    openDetail(id);
    alert("Laporan terkirim dan berstatus Diterima. Terima kasih — laporanmu sudah tercatat pada proyek ini.");
  });
  window.scrollTo({ top: 0 });
}

function backToList() { $("#detailView").hidden = true; $("#listView").hidden = false; renderList(); }
function toggleFollow(id) { followed.has(id) ? followed.delete(id) : followed.add(id); saveFollow(); openDetail(id); renderList(); }

function init() {
  $("#fDesa").innerHTML = `<option value="">Semua desa</option>` + DESA.map((d) => `<option value="${d.id}">Desa ${d.nama}</option>`).join("");
  ["q", "fDesa", "fStatus", "fKategori"].forEach((id) => document.getElementById(id).addEventListener("input", renderList));
  renderSummary(); renderList();
}
init();
