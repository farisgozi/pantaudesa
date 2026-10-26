<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('proyek')->cascadeOnDelete();
            $table->string('tahap', 40);
            $table->string('judul', 200);
            $table->date('tanggal_rencana');
            $table->date('tanggal_realisasi')->nullable();
            $table->string('status', 20);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('progres_bulanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('proyek')->cascadeOnDelete();
            $table->date('bulan');
            $table->unsignedTinyInteger('rencana');
            $table->unsignedTinyInteger('fisik');
            $table->timestamps();
            $table->unique(['proyek_id', 'bulan']);
        });

        Schema::create('dokumen_proyek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('proyek')->cascadeOnDelete();
            $table->string('jenis', 60);
            $table->boolean('tersedia')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('laporan_warga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('proyek')->cascadeOnDelete();
            $table->foreignId('milestone_id')->nullable()->constrained('milestones')->nullOnDelete();
            $table->string('pelapor_nama', 100);
            $table->text('isi');
            $table->string('status', 20)->default('diterima');
            $table->text('tanggapan_admin')->nullable();
            $table->timestamps();
        });

        Schema::create('ikuti_proyek', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengguna_id');
            $table->foreignId('proyek_id')->constrained('proyek')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['pengguna_id', 'proyek_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ikuti_proyek');
        Schema::dropIfExists('laporan_warga');
        Schema::dropIfExists('dokumen_proyek');
        Schema::dropIfExists('progres_bulanan');
        Schema::dropIfExists('milestones');
    }
};
