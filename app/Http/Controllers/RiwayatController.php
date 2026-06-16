<?php

namespace App\Http\Controllers;

use App\Models\RekomendasiSimpan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $dataRiwayat = RekomendasiSimpan::where('user_id', Auth::id())->latest()->get();
        return view('riwayat.index', compact('dataRiwayat'));
    }

    public function show($id)
    {
        $riwayat = RekomendasiSimpan::where('user_id', Auth::id())->findOrFail($id);

        return view('riwayat.LihatRencana', [
            'analisis' => $riwayat->analisis,
            'resep'    => $riwayat->resep,
            'durasi'   => $riwayat->durasi,
            'tanggal'  => $riwayat->created_at
        ]);
    }

    public function destroy($id)
    {
        RekomendasiSimpan::where('user_id', Auth::id())->findOrFail($id)->delete();
        return back()->with('success', 'Riwayat berhasil dihapus.');
    }
}