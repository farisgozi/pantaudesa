<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\Proyek;
use Illuminate\Http\JsonResponse;

class RingkasanController extends Controller
{
    public function index(): JsonResponse
    {
        $proyek = Proyek::with('milestones')->get();

        return response()->json([
            'totalProyek' => $proyek->count(),
            'totalPagu' => $proyek->sum('pagu'),
            'totalCair' => $proyek->sum('dana_cair'),
            'terlambat' => $proyek->filter(fn ($p) => $p->isTerlambat())->count(),
            'desa' => Desa::all()->map(fn ($d) => $d->toApiArray())->values()->all(),
        ]);
    }
}
