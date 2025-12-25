<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Poli;
use App\Models\JadwalPeriksa;
use App\Models\DaftarPoli;
use App\Models\Periksa;

class PoliController extends Controller
{
    public function get()
    {
        $jadwals = JadwalPeriksa::with('dokter.poli')->get();
        $riwayatDaftar = DaftarPoli::with('periksas')
            ->where('id_pasien', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pasien.daftar-poli.index', compact('jadwals', 'riwayatDaftar'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required',
            'keluhan'   => 'required',
        ]);

        $antreanTerakhir = DaftarPoli::where('id_jadwal', $request->id_jadwal)->count();
        
        DaftarPoli::create([
            'id_pasien'  => Auth::id(),
            'id_jadwal'  => $request->id_jadwal,
            'keluhan'    => $request->keluhan,
            'no_antrian' => $antreanTerakhir + 1,
        ]);

        return redirect()->route('pasien.daftar')->with('success', 'Berhasil mendaftar.');
    }

    // --- FITUR BARU: Riwayat Periksa Pasien ---
    public function riwayat()
    {
        // Mengambil data periksa melalui relasi daftarPoli milik pasien yang login
        $riwayatPeriksa = Periksa::with(['daftarPoli.jadwalPeriksa.dokter.poli', 'detailPeriksas.obat'])
            ->whereHas('daftarPoli', function($q) {
                $q->where('id_pasien', Auth::id());
            })
            ->orderBy('tgl_periksa', 'desc')
            ->get();

        return view('pasien.riwayat.index', compact('riwayatPeriksa'));
    }
}