<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    // Nama tabel Indonesia tidak bisa dijamak otomatis oleh Laravel.
    protected $table = 'desa';

    protected $fillable = [
        'nama', 'kecamatan', 'kabupaten',
        'skor_dokumen', 'skor_update', 'skor_respons',
    ];

    public function proyek(): HasMany
    {
        return $this->hasMany(Proyek::class);
    }

    // Skor total = dokumen 40% + update 30% + respons 30% (lihat PRD).
    public function skorTotal(): int
    {
        return (int) round(
            0.4 * (int) $this->skor_dokumen
            + 0.3 * (int) $this->skor_update
            + 0.3 * (int) $this->skor_respons
        );
    }

    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'kecamatan' => $this->kecamatan,
            'kabupaten' => $this->kabupaten,
            'skor' => [
                'dokumen' => (int) $this->skor_dokumen,
                'update' => (int) $this->skor_update,
                'respons' => (int) $this->skor_respons,
                'total' => $this->skorTotal(),
            ],
        ];
    }
}
