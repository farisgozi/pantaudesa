<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proyek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desa')->cascadeOnDelete();
            $table->string('nama', 200);
            $table->string('kategori', 50);
            $table->string('sumber_dana', 50)->default('Dana Desa');
            $table->bigInteger('pagu');
            $table->bigInteger('nilai_kontrak')->nullable();
            $table->bigInteger('dana_cair')->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_target_selesai')->nullable();
            $table->unsignedTinyInteger('progres_fisik')->default(0);
            $table->unsignedTinyInteger('progres_rencana')->default(0);
            $table->string('status', 30);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->index(['desa_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyek');
    }
};
