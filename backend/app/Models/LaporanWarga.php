<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanWarga extends Model
{
    protected $table = 'laporan_warga';

    protected $fillable = [
        'proyek_id', 'milestone_id',
        'pelapor_nama', 'isi', 'status', 'tanggapan_admin',
    ];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function toApiArray(): array
    {
        return [
            'nama' => $this->pelapor_nama,
            'isi' => $this->isi,
            'milestone' => $this->milestone ? $this->milestone->judul : '',
            'status' => $this->status,
            'tanggapan' => $this->tanggapan_admin ?? '',
        ];
    }
}
