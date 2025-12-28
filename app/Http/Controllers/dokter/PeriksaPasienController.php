<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DaftarPoli;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\DetailPeriksa;

class PeriksaPasienController extends Controller
{
    /**
     * Menampilkan daftar pasien yang akan diperiksa
     */
    public function index()
    {
        $idDokter = Auth::id();

        $daftarPasien = DaftarPoli::with([
            'pasien',
            'jadwalPeriksa',
            'periksas'
        ])
        ->whereHas('jadwalPeriksa', function ($query) use ($idDokter) {
            $query->where('id_dokter', $idDokter);
        })
        ->orderBy('no_antrian')
        ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    /**
     * Menampilkan form pemeriksaan pasien
     */
    public function create($id)
    {
        $obats = Obat::all();

        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    /**
     * Menyimpan hasil pemeriksaan pasien
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required',
            'obat_json'      => 'required',
            'catatan'        => 'required',
            'biaya_periksa'  => 'required|numeric'
        ]);

        // Decode obat JSON
        $obatList = json_decode($request->obat_json, true);

        // VALIDASI STOK: Pastikan semua obat yang dipilih memiliki stok yang cukup
        foreach ($obatList as $item) {
            $obat = Obat::find($item['id']);
            if (!$obat || $obat->stok < 1) {
                return redirect()->back()
                    ->with('message', "Gagal! Stok obat " . ($obat ? $obat->nama_obat : 'tersebut') . " sudah habis.")
                    ->with('type', 'danger'); // Handling stok habis
            }
        }

        // Simpan data pemeriksaan
        $periksa = Periksa::create([
            'id_daftar_poli' => $request->id_daftar_poli,
            'tgl_periksa'    => now(),
            'catatan'        => $request->catatan,
            'biaya_periksa'  => $request->biaya_periksa + 150000 // Biaya obat + Jasa Dokter
        ]);

        // Simpan detail obat DAN KURANGI STOK OTOMATIS
        foreach ($obatList as $item) {
            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat'    => $item['id']
            ]);

            // Mengurangi stok obat di database
            $obat = Obat::find($item['id']);
            $obat->stok = $obat->stok - 1; // Mengurangi stok sebanyak 1 unit
            $obat->save();
        }

        return redirect()
            ->route('periksa.pasien.index')
            ->with('message', 'Pasien berhasil diperiksa dan stok obat telah dikurangi.')
            ->with('type', 'success');
    }
}