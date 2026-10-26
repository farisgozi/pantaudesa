<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IkutiProyek;
use App\Models\Proyek;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Proyek::with(['desa', 'milestones', 'progresBulanan', 'dokumen', 'laporan.milestone']);

        // input() mengembalikan string biasa (bukan Stringable), supaya
        // perbandingan status dan mb_strtolower() berperilaku benar.
        $statusFilter = (string) $request->input('status', '');
        if ($request->filled('q')) {
            // LOWER(...) LIKE jalan di PostgreSQL maupun SQLite
            // (operator ILIKE hanya ada di PostgreSQL, jadi tidak dipakai).
            $query->whereRaw('LOWER(nama) LIKE ?', ['%' . mb_strtolower((string) $request->input('q')) . '%']);
        }
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->integer('desa_id'));
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', (string) $request->input('kategori'));
        }
        if ($statusFilter !== '' && $statusFilter !== 'terlambat') {
            $query->where('status', $statusFilter);
        }

        $list = $query->get()->map(fn ($p) => $p->toApiArray())->values()->all();

        // Filter "terlambat" dilakukan di PHP karena flag-nya gabungan
        // status + deviasi + milestone (lihat Proyek::isTerlambat()).
        if ($statusFilter === 'terlambat') {
            $list = array_values(array_filter($list, fn ($p) => $p['terlambat']));
        }

        return response()->json($list);
    }

    public function show(int $id): JsonResponse
    {
        $proyek = Proyek::with(['desa', 'milestones', 'progresBulanan', 'dokumen', 'laporan.milestone'])
            ->findOrFail($id);

        return response()->json($proyek->toApiArray());
    }

    // MVP demo: terima pengguna_id dari body. Saat Sanctum aktif,
    // ganti dengan $request->user()->id dan lindungi route dengan auth:sanctum.
    public function ikuti(Request $request, int $id): JsonResponse
    {
        $request->validate(['pengguna_id' => 'required|integer']);
        Proyek::findOrFail($id);

        $ikuti = IkutiProyek::firstOrCreate([
            'pengguna_id' => $request->integer('pengguna_id'),
            'proyek_id' => $id,
        ]);

        return response()->json(['ikuti' => true, 'id' => $ikuti->id], 201);
    }
}
