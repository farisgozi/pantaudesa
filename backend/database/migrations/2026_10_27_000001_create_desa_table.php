<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('kecamatan', 100);
            $table->string('kabupaten', 100);
            // Komponen skor keterbukaan (total = 40/30/30, lihat Desa::skorTotal()).
            $table->unsignedTinyInteger('skor_dokumen')->default(0);
            $table->unsignedTinyInteger('skor_update')->default(0);
            $table->unsignedTinyInteger('skor_respons')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desa');
    }
};
