<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antri')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
{
    $request->validate([
        'obat_json'    => 'required',
        'catatan'      => 'nullable|string',
        'biaya_periksa' => 'required|integer',
    ]);

    $obatIds = json_decode($request->obat_json, true);


    foreach ($obatIds as $idObat) {
        $obat = Obat::findOrFail($idObat);

        if ($obat->isStokHabis()) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Stok obat \"{$obat->nama_obat}\" sudah habis. Resep tidak dapat disimpan.");
        }
    }

    
    \DB::transaction(function () use ($request, $obatIds) {

        $periksa = Periksa::create([
            'id_daftar_poli' => $request->id_daftar_poli,
            'tgl_periksa'    => now(),
            'catatan'        => $request->catatan,
            'biaya_periksa'  => $request->biaya_periksa + 150000,
        ]);

        foreach ($obatIds as $idObat) {
            $obat = Obat::findOrFail($idObat);

            
            $obat->decrement('stok', 1);

            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat'    => $idObat,
            ]);
        }
    });

    return redirect()->route('periksa-pasien.index')
        ->with('success', 'Data periksa berhasil disimpan.');
}
}