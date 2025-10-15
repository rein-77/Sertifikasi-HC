<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SertifikatController extends Controller
{
    public function index(): JsonResponse
    {
        $sertifikats = Sertifikat::query()->latest()->paginate(15);

        return response()->json($sertifikats);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_sertifikat' => 'required|string|max:50|unique:sertifikats,kode_sertifikat',
            'bidang' => 'nullable|string|max:100',
            'jenjang' => 'nullable|string|max:50',
            'nama_penerbit' => 'nullable|string|max:150',
            'keterangan' => 'nullable|string',
        ]);

        $sertifikat = Sertifikat::create($validated);

        return response()->json($sertifikat, Response::HTTP_CREATED);
    }

    public function show(Sertifikat $sertifikat): JsonResponse
    {
        return response()->json($sertifikat);
    }

    public function update(Request $request, Sertifikat $sertifikat): JsonResponse
    {
        $validated = $request->validate([
            'kode_sertifikat' => 'sometimes|required|string|max:50|unique:sertifikats,kode_sertifikat,' . $sertifikat->id,
            'bidang' => 'nullable|string|max:100',
            'jenjang' => 'nullable|string|max:50',
            'nama_penerbit' => 'nullable|string|max:150',
            'keterangan' => 'nullable|string',
        ]);

        $sertifikat->update($validated);

        return response()->json($sertifikat);
    }

    public function destroy(Sertifikat $sertifikat): Response
    {
        $sertifikat->delete();

        return response()->noContent();
    }
}
