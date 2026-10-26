<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenProyek extends Model
{
    protected $table = 'dokumen_proyek';

    protected $fillable = ['proyek_id', 'jenis', 'tersedia', 'catatan'];

    protected $casts = ['tersedia' => 'boolean'];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }
}
