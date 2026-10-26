<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\DokumenProyek;
use App\Models\LaporanWarga;
use App\Models\Milestone;
use App\Models\ProgresBulanan;
use App\Models\Proyek;
use Illuminate\Database\Seeder;

// DATA DEMO — fiktif untuk lomba, digenerate dari prototype/data.js.
// Bukan data resmi pemerintah. Ganti dengan data riil yang sah sebelum dipakai publik.
class PantauDesaSeeder extends Seeder
{
    public function run(): void
    {
        $desa1 = Desa::create(['nama' => 'Sukamaju', 'kecamatan' => 'Cibinong', 'kabupaten' => 'Bogor', 'skor_dokumen' => 82, 'skor_update' => 72, 'skor_respons' => 80]);
        $desa2 = Desa::create(['nama' => 'Mekarsari', 'kecamatan' => 'Cibinong', 'kabupaten' => 'Bogor', 'skor_dokumen' => 90, 'skor_update' => 85, 'skor_respons' => 82]);
        $desa3 = Desa::create(['nama' => 'Sinarjaya', 'kecamatan' => 'Citeureup', 'kabupaten' => 'Bogor', 'skor_dokumen' => 75, 'skor_update' => 68, 'skor_respons' => 70]);
        $desa4 = Desa::create(['nama' => 'Cibadak', 'kecamatan' => 'Citeureup', 'kabupaten' => 'Bogor', 'skor_dokumen' => 88, 'skor_update' => 80, 'skor_respons' => 82]);

        $proyek1 = Proyek::create(['desa_id' => $desa1->id, 'nama' => 'Pengaspalan Jalan Raya Sukamaju', 'kategori' => 'jalan', 'pagu' => 450000000, 'nilai_kontrak' => 425000000, 'dana_cair' => 255000000, 'tanggal_mulai' => '2026-05-10', 'tanggal_target_selesai' => '2026-11-30', 'progres_fisik' => 42, 'progres_rencana' => 60, 'status' => 'terlambat', 'deskripsi' => 'Pengaspalan 1,2 km jalan utama desa, termasuk perbaikan bahu jalan dan marka.']);
        $milestone = Milestone::create(['proyek_id' => $proyek1->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-04-20', 'tanggal_realisasi' => '2026-04-18', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek1->id, 'tahap' => 'kontrak', 'judul' => 'Penandatanganan kontrak', 'tanggal_rencana' => '2026-05-05', 'tanggal_realisasi' => '2026-05-06', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek1->id, 'tahap' => 'pencairan', 'judul' => 'Pencairan tahap 1 (60%)', 'tanggal_rencana' => '2026-05-20', 'tanggal_realisasi' => '2026-05-22', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek1->id, 'tahap' => 'pelaksanaan', 'judul' => 'Pengaspalan lapis 1', 'tanggal_rencana' => '2026-09-20', 'tanggal_realisasi' => null, 'status' => 'terlambat', 'catatan' => 'Tertunda karena curah hujan tinggi pada September.']);
        $milestone = Milestone::create(['proyek_id' => $proyek1->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2026-11-30', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek1->id, 'bulan' => '2026-05-01', 'rencana' => 5, 'fisik' => 5]);
        ProgresBulanan::create(['proyek_id' => $proyek1->id, 'bulan' => '2026-06-01', 'rencana' => 15, 'fisik' => 14]);
        ProgresBulanan::create(['proyek_id' => $proyek1->id, 'bulan' => '2026-07-01', 'rencana' => 30, 'fisik' => 26]);
        ProgresBulanan::create(['proyek_id' => $proyek1->id, 'bulan' => '2026-08-01', 'rencana' => 45, 'fisik' => 35]);
        ProgresBulanan::create(['proyek_id' => $proyek1->id, 'bulan' => '2026-09-01', 'rencana' => 60, 'fisik' => 40]);
        ProgresBulanan::create(['proyek_id' => $proyek1->id, 'bulan' => '2026-10-01', 'rencana' => 60, 'fisik' => 42]);
        DokumenProyek::create(['proyek_id' => $proyek1->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek1->id, 'jenis' => 'SPK / kontrak', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek1->id, 'jenis' => 'BA serah terima', 'tersedia' => false]);
        LaporanWarga::create(['proyek_id' => $proyek1->id, 'milestone_id' => Milestone::where('proyek_id', $proyek1->id)->where('judul', 'Pengaspalan lapis 1')->value('id'), 'pelapor_nama' => 'Warga RT 02', 'isi' => 'Aspal di tikungan dekat masjid sudah retak lagi.', 'status' => 'ditindaklanjuti', 'tanggapan_admin' => 'Sudah dijadwalkan perbaikan pada lapis 2.']);

        $proyek2 = Proyek::create(['desa_id' => $desa1->id, 'nama' => 'Pembangunan Drainase RT 02/03', 'kategori' => 'drainase', 'pagu' => 280000000, 'nilai_kontrak' => 265000000, 'dana_cair' => 265000000, 'tanggal_mulai' => '2026-06-01', 'tanggal_target_selesai' => '2026-10-31', 'progres_fisik' => 78, 'progres_rencana' => 75, 'status' => 'pelaksanaan', 'deskripsi' => 'Drainase beton 800 meter untuk mengurangi genangan di musim hujan.']);
        $milestone = Milestone::create(['proyek_id' => $proyek2->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-05-15', 'tanggal_realisasi' => '2026-05-14', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek2->id, 'tahap' => 'kontrak', 'judul' => 'Penandatanganan kontrak', 'tanggal_rencana' => '2026-05-28', 'tanggal_realisasi' => '2026-05-28', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek2->id, 'tahap' => 'pelaksanaan', 'judul' => 'Pemasangan drainase tahap 2', 'tanggal_rencana' => '2026-10-15', 'tanggal_realisasi' => '2026-10-12', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek2->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2026-10-31', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek2->id, 'bulan' => '2026-05-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek2->id, 'bulan' => '2026-06-01', 'rencana' => 10, 'fisik' => 12]);
        ProgresBulanan::create(['proyek_id' => $proyek2->id, 'bulan' => '2026-07-01', 'rencana' => 30, 'fisik' => 32]);
        ProgresBulanan::create(['proyek_id' => $proyek2->id, 'bulan' => '2026-08-01', 'rencana' => 50, 'fisik' => 52]);
        ProgresBulanan::create(['proyek_id' => $proyek2->id, 'bulan' => '2026-09-01', 'rencana' => 65, 'fisik' => 68]);
        ProgresBulanan::create(['proyek_id' => $proyek2->id, 'bulan' => '2026-10-01', 'rencana' => 75, 'fisik' => 78]);
        DokumenProyek::create(['proyek_id' => $proyek2->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek2->id, 'jenis' => 'SPK / kontrak', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek2->id, 'jenis' => 'BA serah terima', 'tersedia' => false]);

        $proyek3 = Proyek::create(['desa_id' => $desa1->id, 'nama' => 'Renovasi Balai Warga Sukamaju', 'kategori' => 'fasilitas_umum', 'pagu' => 320000000, 'nilai_kontrak' => 300000000, 'dana_cair' => 150000000, 'tanggal_mulai' => '2026-07-01', 'tanggal_target_selesai' => '2026-12-20', 'progres_fisik' => 35, 'progres_rencana' => 40, 'status' => 'pelaksanaan', 'deskripsi' => 'Perbaikan atap, lantai aula, dan toilet balai warga.']);
        $milestone = Milestone::create(['proyek_id' => $proyek3->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-06-10', 'tanggal_realisasi' => '2026-06-09', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek3->id, 'tahap' => 'pelaksanaan', 'judul' => 'Pekerjaan atap selesai', 'tanggal_rencana' => '2026-09-30', 'tanggal_realisasi' => '2026-10-02', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek3->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2026-12-20', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek3->id, 'bulan' => '2026-05-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek3->id, 'bulan' => '2026-06-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek3->id, 'bulan' => '2026-07-01', 'rencana' => 10, 'fisik' => 8]);
        ProgresBulanan::create(['proyek_id' => $proyek3->id, 'bulan' => '2026-08-01', 'rencana' => 25, 'fisik' => 22]);
        ProgresBulanan::create(['proyek_id' => $proyek3->id, 'bulan' => '2026-09-01', 'rencana' => 35, 'fisik' => 32]);
        ProgresBulanan::create(['proyek_id' => $proyek3->id, 'bulan' => '2026-10-01', 'rencana' => 40, 'fisik' => 35]);
        DokumenProyek::create(['proyek_id' => $proyek3->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek3->id, 'jenis' => 'SPK / kontrak', 'tersedia' => false]);
        DokumenProyek::create(['proyek_id' => $proyek3->id, 'jenis' => 'BA serah terima', 'tersedia' => false]);
        LaporanWarga::create(['proyek_id' => $proyek3->id, 'milestone_id' => Milestone::where('proyek_id', $proyek3->id)->where('judul', 'Pekerjaan atap selesai')->value('id'), 'pelapor_nama' => 'Warga RT 05', 'isi' => 'Material bangunan menutup akses jalan kecil di samping balai.', 'status' => 'selesai', 'tanggapan_admin' => 'Material sudah dipindahkan ke halaman belakang.']);

        $proyek4 = Proyek::create(['desa_id' => $desa2->id, 'nama' => 'Pengerasan Jalan Usaha Tani Mekarsari', 'kategori' => 'jalan', 'pagu' => 380000000, 'nilai_kontrak' => 360000000, 'dana_cair' => 360000000, 'tanggal_mulai' => '2026-04-01', 'tanggal_target_selesai' => '2026-09-30', 'progres_fisik' => 100, 'progres_rencana' => 100, 'status' => 'selesai', 'deskripsi' => 'Pengerasan jalan usaha tani 900 meter menuju lahan pertanian.']);
        $milestone = Milestone::create(['proyek_id' => $proyek4->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-03-10', 'tanggal_realisasi' => '2026-03-08', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek4->id, 'tahap' => 'pelaksanaan', 'judul' => 'Pengerasan selesai', 'tanggal_rencana' => '2026-09-15', 'tanggal_realisasi' => '2026-09-10', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek4->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2026-09-30', 'tanggal_realisasi' => '2026-09-28', 'status' => 'tepat_waktu', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek4->id, 'bulan' => '2026-05-01', 'rencana' => 10, 'fisik' => 12]);
        ProgresBulanan::create(['proyek_id' => $proyek4->id, 'bulan' => '2026-06-01', 'rencana' => 30, 'fisik' => 32]);
        ProgresBulanan::create(['proyek_id' => $proyek4->id, 'bulan' => '2026-07-01', 'rencana' => 55, 'fisik' => 58]);
        ProgresBulanan::create(['proyek_id' => $proyek4->id, 'bulan' => '2026-08-01', 'rencana' => 75, 'fisik' => 78]);
        ProgresBulanan::create(['proyek_id' => $proyek4->id, 'bulan' => '2026-09-01', 'rencana' => 90, 'fisik' => 92]);
        ProgresBulanan::create(['proyek_id' => $proyek4->id, 'bulan' => '2026-10-01', 'rencana' => 100, 'fisik' => 100]);
        DokumenProyek::create(['proyek_id' => $proyek4->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek4->id, 'jenis' => 'SPK / kontrak', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek4->id, 'jenis' => 'BA serah terima', 'tersedia' => true]);

        $proyek5 = Proyek::create(['desa_id' => $desa2->id, 'nama' => 'Sumur Bor dan Jaringan Air Bersih Mekarsari', 'kategori' => 'air_bersih', 'pagu' => 520000000, 'nilai_kontrak' => 495000000, 'dana_cair' => 300000000, 'tanggal_mulai' => '2026-05-15', 'tanggal_target_selesai' => '2026-12-15', 'progres_fisik' => 55, 'progres_rencana' => 55, 'status' => 'pelaksanaan', 'deskripsi' => 'Satu sumur bor dan jaringan pipa ke 120 rumah.']);
        $milestone = Milestone::create(['proyek_id' => $proyek5->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-04-25', 'tanggal_realisasi' => '2026-04-24', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek5->id, 'tahap' => 'pelaksanaan', 'judul' => 'Pengeboran selesai', 'tanggal_rencana' => '2026-08-31', 'tanggal_realisasi' => '2026-08-29', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek5->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2026-12-15', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek5->id, 'bulan' => '2026-05-01', 'rencana' => 5, 'fisik' => 5]);
        ProgresBulanan::create(['proyek_id' => $proyek5->id, 'bulan' => '2026-06-01', 'rencana' => 15, 'fisik' => 16]);
        ProgresBulanan::create(['proyek_id' => $proyek5->id, 'bulan' => '2026-07-01', 'rencana' => 30, 'fisik' => 30]);
        ProgresBulanan::create(['proyek_id' => $proyek5->id, 'bulan' => '2026-08-01', 'rencana' => 45, 'fisik' => 44]);
        ProgresBulanan::create(['proyek_id' => $proyek5->id, 'bulan' => '2026-09-01', 'rencana' => 52, 'fisik' => 52]);
        ProgresBulanan::create(['proyek_id' => $proyek5->id, 'bulan' => '2026-10-01', 'rencana' => 55, 'fisik' => 55]);
        DokumenProyek::create(['proyek_id' => $proyek5->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek5->id, 'jenis' => 'SPK / kontrak', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek5->id, 'jenis' => 'BA serah terima', 'tersedia' => false]);

        $proyek6 = Proyek::create(['desa_id' => $desa2->id, 'nama' => 'Normalisasi Saluran Irigasi Mekarsari', 'kategori' => 'irigasi', 'pagu' => 410000000, 'nilai_kontrak' => 390000000, 'dana_cair' => 120000000, 'tanggal_mulai' => '2026-06-01', 'tanggal_target_selesai' => '2026-11-15', 'progres_fisik' => 28, 'progres_rencana' => 45, 'status' => 'terlambat', 'deskripsi' => 'Pembersihan dan perbaikan tanggul saluran irigasi 1,5 km.']);
        $milestone = Milestone::create(['proyek_id' => $proyek6->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-05-10', 'tanggal_realisasi' => '2026-05-09', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek6->id, 'tahap' => 'pelaksanaan', 'judul' => 'Normalisasi segmen 1', 'tanggal_rencana' => '2026-09-10', 'tanggal_realisasi' => null, 'status' => 'terlambat', 'catatan' => 'Alat berat datang terlambat dua minggu.']);
        $milestone = Milestone::create(['proyek_id' => $proyek6->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2026-11-15', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek6->id, 'bulan' => '2026-05-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek6->id, 'bulan' => '2026-06-01', 'rencana' => 10, 'fisik' => 8]);
        ProgresBulanan::create(['proyek_id' => $proyek6->id, 'bulan' => '2026-07-01', 'rencana' => 25, 'fisik' => 18]);
        ProgresBulanan::create(['proyek_id' => $proyek6->id, 'bulan' => '2026-08-01', 'rencana' => 38, 'fisik' => 24]);
        ProgresBulanan::create(['proyek_id' => $proyek6->id, 'bulan' => '2026-09-01', 'rencana' => 45, 'fisik' => 27]);
        ProgresBulanan::create(['proyek_id' => $proyek6->id, 'bulan' => '2026-10-01', 'rencana' => 45, 'fisik' => 28]);
        DokumenProyek::create(['proyek_id' => $proyek6->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek6->id, 'jenis' => 'SPK / kontrak', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek6->id, 'jenis' => 'BA serah terima', 'tersedia' => false]);
        LaporanWarga::create(['proyek_id' => $proyek6->id, 'milestone_id' => Milestone::where('proyek_id', $proyek6->id)->where('judul', 'Normalisasi segmen 1')->value('id'), 'pelapor_nama' => 'Petani Dusun Tengah', 'isi' => 'Air belum sampai ke petak sawah bagian hilir.', 'status' => 'diterima', 'tanggapan_admin' => null]);

        $proyek7 = Proyek::create(['desa_id' => $desa3->id, 'nama' => 'Pengaspalan Jalan Lingkungan RW 04 Sinarjaya', 'kategori' => 'jalan', 'pagu' => 260000000, 'nilai_kontrak' => null, 'dana_cair' => 0, 'tanggal_mulai' => null, 'tanggal_target_selesai' => '2027-02-28', 'progres_fisik' => 0, 'progres_rencana' => 10, 'status' => 'tender', 'deskripsi' => 'Pengaspalan jalan lingkungan 600 meter. Saat ini masih tahap tender.']);
        $milestone = Milestone::create(['proyek_id' => $proyek7->id, 'tahap' => 'tender', 'judul' => 'Pengumuman tender', 'tanggal_rencana' => '2026-10-31', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek7->id, 'tahap' => 'kontrak', 'judul' => 'Penandatanganan kontrak', 'tanggal_rencana' => '2026-11-30', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek7->id, 'bulan' => '2026-05-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek7->id, 'bulan' => '2026-06-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek7->id, 'bulan' => '2026-07-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek7->id, 'bulan' => '2026-08-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek7->id, 'bulan' => '2026-09-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek7->id, 'bulan' => '2026-10-01', 'rencana' => 10, 'fisik' => 0]);
        DokumenProyek::create(['proyek_id' => $proyek7->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek7->id, 'jenis' => 'SPK / kontrak', 'tersedia' => false]);
        DokumenProyek::create(['proyek_id' => $proyek7->id, 'jenis' => 'BA serah terima', 'tersedia' => false]);

        $proyek8 = Proyek::create(['desa_id' => $desa3->id, 'nama' => 'Pembangunan MCK Umum Pasar Desa Sinarjaya', 'kategori' => 'fasilitas_umum', 'pagu' => 190000000, 'nilai_kontrak' => 175000000, 'dana_cair' => 175000000, 'tanggal_mulai' => '2026-05-01', 'tanggal_target_selesai' => '2026-10-31', 'progres_fisik' => 92, 'progres_rencana' => 90, 'status' => 'pelaksanaan', 'deskripsi' => 'MCK umum 6 bilik di area pasar desa.']);
        $milestone = Milestone::create(['proyek_id' => $proyek8->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-04-05', 'tanggal_realisasi' => '2026-04-04', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek8->id, 'tahap' => 'pelaksanaan', 'judul' => 'Struktur dan atap selesai', 'tanggal_rencana' => '2026-09-20', 'tanggal_realisasi' => '2026-09-18', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek8->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2026-10-31', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek8->id, 'bulan' => '2026-05-01', 'rencana' => 10, 'fisik' => 12]);
        ProgresBulanan::create(['proyek_id' => $proyek8->id, 'bulan' => '2026-06-01', 'rencana' => 30, 'fisik' => 32]);
        ProgresBulanan::create(['proyek_id' => $proyek8->id, 'bulan' => '2026-07-01', 'rencana' => 50, 'fisik' => 52]);
        ProgresBulanan::create(['proyek_id' => $proyek8->id, 'bulan' => '2026-08-01', 'rencana' => 70, 'fisik' => 72]);
        ProgresBulanan::create(['proyek_id' => $proyek8->id, 'bulan' => '2026-09-01', 'rencana' => 85, 'fisik' => 86]);
        ProgresBulanan::create(['proyek_id' => $proyek8->id, 'bulan' => '2026-10-01', 'rencana' => 90, 'fisik' => 92]);
        DokumenProyek::create(['proyek_id' => $proyek8->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek8->id, 'jenis' => 'SPK / kontrak', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek8->id, 'jenis' => 'BA serah terima', 'tersedia' => false]);

        $proyek9 = Proyek::create(['desa_id' => $desa3->id, 'nama' => 'Drainase Jalan Poros Desa Sinarjaya', 'kategori' => 'drainase', 'pagu' => 300000000, 'nilai_kontrak' => 285000000, 'dana_cair' => 170000000, 'tanggal_mulai' => '2026-07-01', 'tanggal_target_selesai' => '2026-12-31', 'progres_fisik' => 48, 'progres_rencana' => 50, 'status' => 'pelaksanaan', 'deskripsi' => 'Drainase 1 km di sepanjang jalan poros desa.']);
        $milestone = Milestone::create(['proyek_id' => $proyek9->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-06-05', 'tanggal_realisasi' => '2026-06-04', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek9->id, 'tahap' => 'pelaksanaan', 'judul' => 'Drainase segmen barat selesai', 'tanggal_rencana' => '2026-10-20', 'tanggal_realisasi' => '2026-10-19', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek9->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2026-12-31', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek9->id, 'bulan' => '2026-05-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek9->id, 'bulan' => '2026-06-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek9->id, 'bulan' => '2026-07-01', 'rencana' => 10, 'fisik' => 9]);
        ProgresBulanan::create(['proyek_id' => $proyek9->id, 'bulan' => '2026-08-01', 'rencana' => 30, 'fisik' => 28]);
        ProgresBulanan::create(['proyek_id' => $proyek9->id, 'bulan' => '2026-09-01', 'rencana' => 42, 'fisik' => 40]);
        ProgresBulanan::create(['proyek_id' => $proyek9->id, 'bulan' => '2026-10-01', 'rencana' => 50, 'fisik' => 48]);
        DokumenProyek::create(['proyek_id' => $proyek9->id, 'jenis' => 'RAB ringkas', 'tersedia' => false]);
        DokumenProyek::create(['proyek_id' => $proyek9->id, 'jenis' => 'SPK / kontrak', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek9->id, 'jenis' => 'BA serah terima', 'tersedia' => false]);

        $proyek10 = Proyek::create(['desa_id' => $desa4->id, 'nama' => 'Jembatan Penghubung Dusun Kidul Cibadak', 'kategori' => 'jalan', 'pagu' => 850000000, 'nilai_kontrak' => 810000000, 'dana_cair' => 400000000, 'tanggal_mulai' => '2026-04-15', 'tanggal_target_selesai' => '2027-01-31', 'progres_fisik' => 50, 'progres_rencana' => 62, 'status' => 'terlambat', 'deskripsi' => 'Jembatan beton 18 meter menggantikan jembatan kayu.']);
        $milestone = Milestone::create(['proyek_id' => $proyek10->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-03-20', 'tanggal_realisasi' => '2026-03-18', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek10->id, 'tahap' => 'pelaksanaan', 'judul' => 'Pondasi dan abutmen selesai', 'tanggal_rencana' => '2026-09-30', 'tanggal_realisasi' => null, 'status' => 'terlambat', 'catatan' => 'Pengiriman material baja tertunda.']);
        $milestone = Milestone::create(['proyek_id' => $proyek10->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2027-01-31', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek10->id, 'bulan' => '2026-05-01', 'rencana' => 5, 'fisik' => 5]);
        ProgresBulanan::create(['proyek_id' => $proyek10->id, 'bulan' => '2026-06-01', 'rencana' => 15, 'fisik' => 14]);
        ProgresBulanan::create(['proyek_id' => $proyek10->id, 'bulan' => '2026-07-01', 'rencana' => 30, 'fisik' => 26]);
        ProgresBulanan::create(['proyek_id' => $proyek10->id, 'bulan' => '2026-08-01', 'rencana' => 45, 'fisik' => 36]);
        ProgresBulanan::create(['proyek_id' => $proyek10->id, 'bulan' => '2026-09-01', 'rencana' => 58, 'fisik' => 45]);
        ProgresBulanan::create(['proyek_id' => $proyek10->id, 'bulan' => '2026-10-01', 'rencana' => 62, 'fisik' => 50]);
        DokumenProyek::create(['proyek_id' => $proyek10->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek10->id, 'jenis' => 'SPK / kontrak', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek10->id, 'jenis' => 'BA serah terima', 'tersedia' => false]);
        LaporanWarga::create(['proyek_id' => $proyek10->id, 'milestone_id' => Milestone::where('proyek_id', $proyek10->id)->where('judul', 'Pondasi dan abutmen selesai')->value('id'), 'pelapor_nama' => 'Warga Dusun Kidul', 'isi' => 'Akses sementara lewat jembatan darurat licin saat hujan.', 'status' => 'ditindaklanjuti', 'tanggapan_admin' => 'Papan kayu anti-selip sudah dipasang.']);

        $proyek11 = Proyek::create(['desa_id' => $desa4->id, 'nama' => 'Jaringan Air Bersih Dusun Wetan Cibadak', 'kategori' => 'air_bersih', 'pagu' => 460000000, 'nilai_kontrak' => 440000000, 'dana_cair' => 440000000, 'tanggal_mulai' => '2026-03-01', 'tanggal_target_selesai' => '2026-08-31', 'progres_fisik' => 100, 'progres_rencana' => 100, 'status' => 'selesai', 'deskripsi' => 'Jaringan pipa ke 95 rumah di Dusun Wetan.']);
        $milestone = Milestone::create(['proyek_id' => $proyek11->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-02-05', 'tanggal_realisasi' => '2026-02-03', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek11->id, 'tahap' => 'pelaksanaan', 'judul' => 'Pemasangan pipa selesai', 'tanggal_rencana' => '2026-08-15', 'tanggal_realisasi' => '2026-08-12', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek11->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2026-08-31', 'tanggal_realisasi' => '2026-08-30', 'status' => 'tepat_waktu', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek11->id, 'bulan' => '2026-05-01', 'rencana' => 10, 'fisik' => 12]);
        ProgresBulanan::create(['proyek_id' => $proyek11->id, 'bulan' => '2026-06-01', 'rencana' => 30, 'fisik' => 32]);
        ProgresBulanan::create(['proyek_id' => $proyek11->id, 'bulan' => '2026-07-01', 'rencana' => 50, 'fisik' => 54]);
        ProgresBulanan::create(['proyek_id' => $proyek11->id, 'bulan' => '2026-08-01', 'rencana' => 70, 'fisik' => 74]);
        ProgresBulanan::create(['proyek_id' => $proyek11->id, 'bulan' => '2026-09-01', 'rencana' => 90, 'fisik' => 92]);
        ProgresBulanan::create(['proyek_id' => $proyek11->id, 'bulan' => '2026-10-01', 'rencana' => 100, 'fisik' => 100]);
        DokumenProyek::create(['proyek_id' => $proyek11->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek11->id, 'jenis' => 'SPK / kontrak', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek11->id, 'jenis' => 'BA serah terima', 'tersedia' => true]);

        $proyek12 = Proyek::create(['desa_id' => $desa4->id, 'nama' => 'Rehabilitasi Jalan Setapak dan Drainase Cibadak', 'kategori' => 'drainase', 'pagu' => 230000000, 'nilai_kontrak' => 215000000, 'dana_cair' => 65000000, 'tanggal_mulai' => '2026-09-01', 'tanggal_target_selesai' => '2027-01-15', 'progres_fisik' => 15, 'progres_rencana' => 15, 'status' => 'kontrak', 'deskripsi' => 'Rehabilitasi jalan setapak 400 meter dan drainase kecil di permukiman padat.']);
        $milestone = Milestone::create(['proyek_id' => $proyek12->id, 'tahap' => 'tender', 'judul' => 'Tender selesai', 'tanggal_rencana' => '2026-08-10', 'tanggal_realisasi' => '2026-08-08', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek12->id, 'tahap' => 'kontrak', 'judul' => 'Penandatanganan kontrak', 'tanggal_rencana' => '2026-08-28', 'tanggal_realisasi' => '2026-08-27', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek12->id, 'tahap' => 'pelaksanaan', 'judul' => 'Pekerjaan dimulai', 'tanggal_rencana' => '2026-09-01', 'tanggal_realisasi' => '2026-09-02', 'status' => 'tepat_waktu', 'catatan' => null]);
        $milestone = Milestone::create(['proyek_id' => $proyek12->id, 'tahap' => 'serah_terima', 'judul' => 'Serah terima pekerjaan', 'tanggal_rencana' => '2027-01-15', 'tanggal_realisasi' => null, 'status' => 'akan_datang', 'catatan' => null]);
        ProgresBulanan::create(['proyek_id' => $proyek12->id, 'bulan' => '2026-05-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek12->id, 'bulan' => '2026-06-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek12->id, 'bulan' => '2026-07-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek12->id, 'bulan' => '2026-08-01', 'rencana' => 0, 'fisik' => 0]);
        ProgresBulanan::create(['proyek_id' => $proyek12->id, 'bulan' => '2026-09-01', 'rencana' => 5, 'fisik' => 5]);
        ProgresBulanan::create(['proyek_id' => $proyek12->id, 'bulan' => '2026-10-01', 'rencana' => 15, 'fisik' => 15]);
        DokumenProyek::create(['proyek_id' => $proyek12->id, 'jenis' => 'RAB ringkas', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek12->id, 'jenis' => 'SPK / kontrak', 'tersedia' => true]);
        DokumenProyek::create(['proyek_id' => $proyek12->id, 'jenis' => 'BA serah terima', 'tersedia' => false]);

    }
}
