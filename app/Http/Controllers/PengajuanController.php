<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\RuangLaboratorium;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    /*
    | fungsi untuk menampilkan daftar pengajuan / riwayat pengajuan
    */
    public function index()
    {
        $data = Pengajuan::orderBy('id', 'desc')->get();
        if (Auth::user()->hak_akses == 'dosen') {
            $data = $data->where('id_dosen', Auth::user()->dosen->id);
        }
        return view('pengajuan.index', compact('data'));
    }

    /*
    | fungsi untuk menampilkan halaman tambah pengajuan
    */
    public function tambah()
    {
        $ruangLab = RuangLaboratorium::all();
        return view('pengajuan.tambah', compact('ruangLab'));
    }

    /*
    | fungsi untuk menyimpan data pengajuan penggunaan laboratorium
    */
    public function simpan(Request $request)
    {
        $validasi = $request->validate([
            'id_dosen' => 'required|exists:tb_dosen,id',
            'id_ruang' => 'required|exists:tb_ruang_lab,id',
            'tanggal_pengajuan' => 'required|date',
            'tanggal_pemakaian' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
        ], [
            'id_ruang.required' => 'Ruang lab harus dipilih.',
            'tanggal_pengajuan.required' => 'Tanggal pengajuan harus diisi.',
            'tanggal_pemakaian.required' => 'Tanggal pemakaian harus diisi.',
            'jam_mulai.required' => 'Jam mulai harus diisi.',
            'jam_selesai.required' => 'Jam selesai harus diisi.',
        ]);

        DB::beginTransaction();
        try {
            Pengajuan::create($validasi);
            DB::commit();
            return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan pengajuan: ' . $e->getMessage()]);
        }
    }

    /*
    | fungsi untuk menyetujui pengajuan
    */
    public function setujui($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        DB::beginTransaction();
        try {
            $pengajuan->status = 'disetujui';
            $pengajuan->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil disetujui.',
                'data' => $pengajuan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pengajuan: ' . $e->getMessage()
            ]);
        }
    }

    /*
    | fungsi untuk menolak pengajuan
    */
    public function tolak($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        DB::beginTransaction();
        try {
            $pengajuan->status = 'ditolak';
            $pengajuan->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil ditolak.',
                'data' => $pengajuan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pengajuan: ' . $e->getMessage()
            ]);
        }
    }

    public function batalkan(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        DB::beginTransaction();
        try {
            $pengajuan->status = 'dibatalkan';
            $pengajuan->keterangan = $request->keterangan;
            $pengajuan->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dibatalkan.',
                'data' => $pengajuan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pengajuan: ' . $e->getMessage()
            ]);
        }
    }

    public function keterangan($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        return response()->json([
            'success' => true,
            'keterangan' => $pengajuan->keterangan ?? 'Tidak ada keterangan',
            'status' => $pengajuan->status
        ]);
    }
}
