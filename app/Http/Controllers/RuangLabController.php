<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RuangLaboratorium;
use Illuminate\Support\Facades\DB;

class RuangLabController extends Controller
{
    /**
     * menampilkan daftar ruang lab.
     */
    public function index()
    {
        $data = RuangLaboratorium::all();
        return view('ruang_lab.index', compact('data'));
    }

    /**
     * fungsi untuk menampilkan halaman tambah ruang lab.
     */
    public function tambah()
    {
        return view('ruang_lab.tambah');
    }

    /**
     * fungsi untuk menyimpan data ruang lab.
     */
    public function simpan(Request $request)
    {
        $validasi = $request->validate([
            'nama_ruang' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:100',
        ], [
            'nama_ruang.required' => 'Nama ruang lab harus diisi.',
            'nama_ruang.max' => 'Nama ruang lab tidak boleh lebih dari 100 karakter.',
            'keterangan.max' => 'Keterangan tidak boleh lebih dari 100 karakter.',
        ]);

        DB::beginTransaction();
        try {
            RuangLaboratorium::create($validasi);
            DB::commit();
            return redirect()->route('ruangLab.index')->with('success', 'Ruang lab berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan data ruang lab.']);
        }
    }

    /**
     * fungsi untuk menampilkan halaman edit ruang lab.
     */
    public function edit(RuangLaboratorium $ruangLab)
    {
        return view('ruang_lab.edit', compact('ruangLab'));
    }

     /**
     * fungsi untuk mengupdate data ruang lab.
     */
    public function update(Request $request, RuangLaboratorium $ruangLab)
    {
        $validasi = $request->validate([
            'nama_ruang' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:100',
        ], [
            'nama_ruang.required' => 'Nama ruang lab harus diisi.',
            'nama_ruang.max' => 'Nama ruang lab tidak boleh lebih dari 100 karakter.',
            'keterangan.max' => 'Keterangan tidak boleh lebih dari 100 karakter.',
        ]);
        DB::beginTransaction();
        try {
            $ruangLab->update($validasi);
            DB::commit();
            return redirect()->route('ruangLab.index')->with('success', "Ruang {$ruangLab->nama_ruang} berhasil diupdate.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal mengupdate data ruang lab.']);
        }
    }

    /**
     * fungsi untuk menghapus ruang lab.
     */
    public function hapus(RuangLaboratorium $ruangLab)
    {
        $ruangLab->delete();
        return redirect()->route('ruangLab.index')->with('success', "Ruang {$ruangLab->nama_ruang} berhasil dihapus.");
    }
}
