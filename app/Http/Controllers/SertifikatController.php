<?php

namespace App\Http\Controllers;

use App\Http\Requests\SertifikatRequest;
use App\Models\Sertifikat;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $sertifikats = Sertifikat::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('kode_sertifikat', 'like', "%{$search}%")
                        ->orWhere('bidang', 'like', "%{$search}%")
                        ->orWhere('jenjang', 'like', "%{$search}%")
                        ->orWhere('nama_penerbit', 'like', "%{$search}%");
                });
            })
            ->orderBy('kode_sertifikat')
            ->paginate(10)
            ->withQueryString();

        return view('sertifikat.index', [
            'sertifikats' => $sertifikats,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('sertifikat.create');
    }

    public function store(SertifikatRequest $request)
    {
        Sertifikat::create($request->validated());

        return redirect()
            ->route('sertifikats.index')
            ->with('status', __('Sertifikat berhasil ditambahkan.'));
    }

    public function edit(Sertifikat $sertifikat)
    {
        return view('sertifikat.edit', compact('sertifikat'));
    }

    public function update(SertifikatRequest $request, Sertifikat $sertifikat)
    {
        $sertifikat->update($request->validated());

        return redirect()
            ->route('sertifikats.index')
            ->with('status', __('Data sertifikat berhasil diperbarui.'));
    }

    public function destroy(Sertifikat $sertifikat)
    {
        $sertifikat->delete();

        return redirect()
            ->route('sertifikats.index')
            ->with('status', __('Sertifikat berhasil dihapus.'));
    }
}
