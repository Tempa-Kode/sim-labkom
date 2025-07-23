<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalLaboratorium;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalLabController extends Controller
{
    /**
     * fungsi untuk menampilkan data jadwal lab
     */
    public function index()
    {
        $data = JadwalLaboratorium::with(['ruangLaboratorium', 'dosen'])
            ->orderBy('hari', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        return view('jadwal_lab.index', compact('data'));
    }

    /**
     * fungsi untuk menampilkan halaman tambah jadwal lab
     */
    public function tambah()
    {
        $ruangLab = \App\Models\RuangLaboratorium::all();
        $dosen = \App\Models\Dosen::all();
        return view('jadwal_lab.tambah', compact('ruangLab', 'dosen'));
    }

    /**
     * fungsi untuk menyimpan data jadwal lab
     */
    public function simpan(Request $request)
    {
        $validasi = $request->validate([
            'id_ruang_lab' => 'required|exists:tb_ruang_lab,id',
            'hari' => 'required|string|max:10',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'id_dosen' => 'required|exists:tb_dosen,id',
            'status_ruang' => 'required|string|max:20',
        ], [
            'id_ruang_lab.required' => 'Ruang lab harus dipilih.',
            'hari.required' => 'Hari harus diisi.',
            'waktu_mulai.required' => 'Waktu mulai harus diisi.',
            'waktu_selesai.required' => 'Waktu selesai harus diisi.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
            'id_dosen.required' => 'Dosen harus dipilih.',
            'status_ruang.required' => 'Status ruang harus dipilih.',
        ]);

        DB::beginTransaction();

        try{
            JadwalLaboratorium::create($validasi);
            DB::commit();
            return redirect()->route('jadwalLab.index')->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menambahkan jadwal.']);
        }
    }

    /**
     * fungsi untuk menampilkan halaman edit jadwal lab
     */
    public function edit($id)
    {
        $jadwal = JadwalLaboratorium::find($id);
        if (!$jadwal) {
            return redirect()->route('jadwalLab.index')->withErrors(['error' => 'Jadwal tidak ditemukan.']);
        }
        $ruangLab = \App\Models\RuangLaboratorium::all();
        $dosen = \App\Models\Dosen::all();

        return view('jadwal_lab.edit', compact('jadwal', 'ruangLab', 'dosen'));
    }

    /**
     * fungsi untuk menyimpan perubahan data jadwal lab
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalLaboratorium::find($id);
        if (!$jadwal) {
            return redirect()->route('jadwalLab.index')->withErrors(['error' => 'Jadwal tidak ditemukan.']);
        }
        $validasi = $request->validate([
            'id_ruang_lab' => 'required|exists:tb_ruang_lab,id',
            'hari' => 'required|string|max:10',
            'waktu_mulai' => 'required|date_format:H:i:s',
            'waktu_selesai' => 'required|date_format:H:i:s|after:waktu_mulai',
            'id_dosen' => 'required|exists:tb_dosen,id',
            'status_ruang' => 'required|string|max:20',
        ], [
            'id_ruang_lab.required' => 'Ruang lab harus dipilih.',
            'hari.required' => 'Hari harus diisi.',
            'waktu_mulai.required' => 'Waktu mulai harus diisi.',
            'waktu_selesai.required' => 'Waktu selesai harus diisi.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
            'id_dosen.required' => 'Dosen harus dipilih.',
            'status_ruang.required' => 'Status ruang harus dipilih.',
        ]);
        DB::beginTransaction();
        try {
            $jadwal->update($validasi);
            DB::commit();
            return redirect()->route('jadwalLab.index')->with('success', 'Jadwal berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui jadwal.']);
        }
    }

    /**
     * fungsi untuk menghapus data jadwal lab
     */
    public function hapus($id)
    {
        $jadwal = JadwalLaboratorium::find($id);
        if (!$jadwal) {
            return redirect()->route('jadwalLab.index')->withErrors(['error' => 'Jadwal tidak ditemukan.']);
        }
        DB::beginTransaction();
        try {
            $jadwal->delete();
            DB::commit();
            return redirect()->route('jadwalLab.index')->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus jadwal.']);
        }
    }

    /**
     * fungsi untuk export data jadwal lab ke pdf
     */
    public function exportPdf()
    {
        $data = JadwalLaboratorium::with(['ruangLaboratorium', 'dosen'])
            ->orderBy('hari', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        $pdf = Pdf::loadView('jadwal_lab.export_pdf', compact('data'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
    }
}
