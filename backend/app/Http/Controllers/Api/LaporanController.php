<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LaporanWarga;
use App\Models\Milestone;
use App\Models\Proyek;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function store(Request $request, int $proyekId): JsonResponse
    {
        $data = $request->validate([
            'pelapor_nama' => 'required|string|max:100',
            'isi' => 'required|string',
            'milestone_id' => 'nullable|integer|exists:milestones,id',
            'milestone_judul' => 'nullable|string|max:200',
        ]);

        $proyek = Proyek::findOrFail($proyekId);

        // Frontend mengirim judul milestone (mode demo). Kalau ada milestone_id
        // pakai itu; kalau hanya judul, cari milestone proyek tersebut.
        $milestoneId = $data['milestone_id'] ?? null;
        if (! $milestoneId && ! empty($data['milestone_judul'])) {
            $milestoneId = Milestone::where('proyek_id', $proyek->id)
                ->where('judul', $data['milestone_judul'])
                ->value('id');
        }

        $laporan = LaporanWarga::create([
            'proyek_id' => $proyek->id,
            'milestone_id' => $milestoneId,
            'pelapor_nama' => $data['pelapor_nama'],
            'isi' => $data['isi'],
            'status' => 'diterima',
        ]);
        $laporan->load('milestone');

        return response()->json($laporan->toApiArray(), 201);
    }
}
