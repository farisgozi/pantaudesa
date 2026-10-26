-- PantauDesa — skema PostgreSQL (produksi)
-- Data demo ada di seed.sql. Jangan pakai seed untuk publikasi sebagai data resmi.

CREATE TABLE desa (
  id SERIAL PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  kecamatan VARCHAR(100) NOT NULL,
  kabupaten VARCHAR(100) NOT NULL,
  created_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE pengguna (
  id SERIAL PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  peran VARCHAR(20) NOT NULL CHECK (peran IN ('warga', 'operator_desa', 'pemeriksa')),
  desa_id INT REFERENCES desa(id),
  created_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE proyek (
  id SERIAL PRIMARY KEY,
  desa_id INT NOT NULL REFERENCES desa(id),
  nama VARCHAR(200) NOT NULL,
  kategori VARCHAR(50) NOT NULL CHECK (kategori IN ('jalan', 'drainase', 'air_bersih', 'fasilitas_umum', 'irigasi')),
  sumber_dana VARCHAR(50) NOT NULL DEFAULT 'Dana Desa',
  pagu BIGINT NOT NULL CHECK (pagu > 0),
  nilai_kontrak BIGINT CHECK (nilai_kontrak IS NULL OR nilai_kontrak > 0),
  dana_cair BIGINT NOT NULL DEFAULT 0 CHECK (dana_cair >= 0),
  tanggal_mulai DATE,
  tanggal_target_selesai DATE,
  progres_fisik INT NOT NULL DEFAULT 0 CHECK (progres_fisik BETWEEN 0 AND 100),
  progres_rencana INT NOT NULL DEFAULT 0 CHECK (progres_rencana BETWEEN 0 AND 100),
  status VARCHAR(30) NOT NULL CHECK (status IN ('tender', 'kontrak', 'pelaksanaan', 'selesai', 'terlambat')),
  deskripsi TEXT,
  created_at TIMESTAMPTZ DEFAULT now()
  -- Aturan bisnis (bukan constraint kaku, supaya admin bisa koreksi):
  -- status 'terlambat' bila ada milestone lewat deadline dan belum selesai,
  -- atau (progres_rencana - progres_fisik) > 10.
);

CREATE TABLE milestone (
  id SERIAL PRIMARY KEY,
  proyek_id INT NOT NULL REFERENCES proyek(id) ON DELETE CASCADE,
  tahap VARCHAR(40) NOT NULL CHECK (tahap IN ('tender', 'kontrak', 'pencairan', 'pelaksanaan', 'serah_terima')),
  judul VARCHAR(200) NOT NULL,
  tanggal_rencana DATE NOT NULL,
  tanggal_realisasi DATE,
  status VARCHAR(20) NOT NULL CHECK (status IN ('akan_datang', 'selesai', 'terlambat', 'tepat_waktu')),
  catatan TEXT
);

CREATE TABLE progres_bulanan (
  id SERIAL PRIMARY KEY,
  proyek_id INT NOT NULL REFERENCES proyek(id) ON DELETE CASCADE,
  bulan DATE NOT NULL,
  rencana INT NOT NULL CHECK (rencana BETWEEN 0 AND 100),
  fisik INT NOT NULL CHECK (fisik BETWEEN 0 AND 100),
  UNIQUE (proyek_id, bulan)
);

CREATE TABLE dokumen_proyek (
  id SERIAL PRIMARY KEY,
  proyek_id INT NOT NULL REFERENCES proyek(id) ON DELETE CASCADE,
  jenis VARCHAR(60) NOT NULL, -- contoh: RAB ringkas, SPK, BA serah terima
  tersedia BOOLEAN NOT NULL DEFAULT FALSE,
  catatan TEXT
);

CREATE TABLE laporan_warga (
  id SERIAL PRIMARY KEY,
  proyek_id INT NOT NULL REFERENCES proyek(id) ON DELETE CASCADE,
  milestone_id INT REFERENCES milestone(id),
  pelapor_nama VARCHAR(100) NOT NULL,
  isi TEXT NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'diterima'
    CHECK (status IN ('diterima', 'ditindaklanjuti', 'selesai', 'ditolak')),
  tanggapan_admin TEXT,
  created_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE ikuti_proyek (
  pengguna_id INT NOT NULL REFERENCES pengguna(id) ON DELETE CASCADE,
  proyek_id INT NOT NULL REFERENCES proyek(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ DEFAULT now(),
  PRIMARY KEY (pengguna_id, proyek_id)
);

CREATE INDEX idx_proyek_desa ON proyek(desa_id);
CREATE INDEX idx_proyek_status ON proyek(status);
CREATE INDEX idx_milestone_proyek ON milestone(proyek_id);
CREATE INDEX idx_laporan_proyek ON laporan_warga(proyek_id);
