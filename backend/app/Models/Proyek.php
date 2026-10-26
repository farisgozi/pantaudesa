<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    // Nama tabel Indonesia tidak bisa dijamak otomatis oleh Laravel.
    protected $table = 'proyek';

    protected $fillable = [
        'desa_id', 'nama', 'kategori', 'sumber_dana',
        'pagu', 'nilai_kontrak', 'dana_cair',
        'tanggal_mulai', 'tanggal_target_selesai',
        'progres_fisik', 'progres_rencana', 'status', 'deskripsi',
    ];

    protected $casts = [
        'pagu' => 'integer',
        'nilai_kontrak' => 'integer',
        'dana_cair' => 'integer',
        'progres_fisik' => 'integer',
        'progres_rencana' => 'integer',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function progresBulanan(): HasMany
    {
        return $this->hasMany(ProgresBulanan::class)->orderBy('bulan');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenProyek::class);
    }

    public function laporan(): HasMany
    {
        return $this->hasMany(LaporanWarga::class)->latest();
    }

    public function deviasi(): int
    {
        return $this->progres_fisik - $this->progres_rencana;
    }

    // Aturan flag terlambat — sama dengan docs/api-spec.md dan frontend lib/api.ts.
    public function isTerlambat(): bool
    {
        if ($this->status === 'terlambat') {
            return true;
        }
        if (($this->progres_rencana - $this->progres_fisik) > 10) {
            return true;
        }
        $milestones = $this->relationLoaded('milestones')
            ? $this->milestones
            : $this->milestones()->get();

        return $milestones->contains(fn ($m) => $m->status === 'terlambat');
    }

    public function toApiArray(): array
    {
        $bulan = $this->relationLoaded('progresBulanan')
            ? $this->progresBulanan
            : $this->progresBulanan()->orderBy('bulan')->get();

        return [
            'id' => $this->id,
            'desa' => $this->desa ? $this->desa->toApiArray() : null,
            'nama' => $this->nama,
            'kategori' => $this->kategori,
            'pagu' => $this->pagu,
            'kontrak' => $this->nilai_kontrak,
            'cair' => $this->dana_cair,
            'mulai' => $this->tanggal_mulai,
            'target' => $this->tanggal_target_selesai,
            'fisik' => $this->progres_fisik,
            'rencana' => $this->progres_rencana,
            'deviasi' => $this->deviasi(),
            'terlambat' => $this->isTerlambat(),
            'status' => $this->status,
            'deskripsi' => $this->deskripsi,
            'milestones' => $this->milestones->map(fn ($m) => [
                'tahap' => $m->tahap,
                'judul' => $m->judul,
                'rencana' => $m->tanggal_rencana,
                'realisasi' => $m->tanggal_realisasi,
                'status' => $m->status,
                'catatan' => $m->catatan,
            ])->values()->all(),
            'kurvaRencana' => $bulan->pluck('rencana')->all(),
            'kurvaFisik' => $bulan->pluck('fisik')->all(),
            'dokumen' => $this->dokumen->map(fn ($d) => [
                'jenis' => $d->jenis,
                'tersedia' => (bool) $d->tersedia,
            ])->values()->all(),
            'laporan' => $this->laporan->map(fn ($l) => $l->toApiArray())->values()->all(),
        ];
    }
}
