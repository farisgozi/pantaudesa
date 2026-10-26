<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgresBulanan extends Model
{
    protected $table = 'progres_bulanan';

    protected $fillable = ['proyek_id', 'bulan', 'rencana', 'fisik'];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class);
    }
}
