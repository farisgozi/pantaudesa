<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IkutiProyek extends Model
{
    protected $table = 'ikuti_proyek';

    protected $fillable = ['pengguna_id', 'proyek_id'];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }
}
