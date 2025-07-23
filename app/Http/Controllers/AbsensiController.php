<?php

namespace App\Http\Controllers;

use App\Models\AbsensiAslab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        if($request->query('tanggal')){
            $tanggal = $request->query('tanggal');
            $data = AbsensiAslab::where('tanggal', $tanggal)->get();
        } else {
            $data = AbsensiAslab::all();
        }

        return view('absensi.index', compact('data'));
    }

    public function riwayatAbsensi()
    {
        $data = AbsensiAslab::where('id_pengguna', Auth::user()->id)->orderBy('tanggal', 'desc')->get();
        return view('absensi.riwayat', compact('data'));
    }

    public function absensi(Request $request)
    {
        $validasi = $request->validate([
            'hari' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
        ], [
            'hari.required' => 'Hari wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ]);

        $tanggal = date('Y-m-d');
        $absensi = AbsensiAslab::where('id_pengguna', Auth::user()->id)
            ->where('tanggal', $tanggal)
            ->first();

        if ($absensi) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi hari ini.');
        }

        DB::beginTransaction();

        try {
            $validasi['id_pengguna'] = Auth::user()->id;
            AbsensiAslab::create($validasi);
            DB::commit();
            return redirect()->back()->with('success', 'Absensi berhasil dilakukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan absensi: ' . $e->getMessage());
        }
    }
}
