<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\JsonResponse;

class DesaController extends Controller
{
    public function skor(int $id): JsonResponse
    {
        $desa = Desa::findOrFail($id);

        return response()->json($desa->toApiArray());
    }
}
