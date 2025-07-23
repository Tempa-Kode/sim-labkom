<?php

namespace App\Http\Controllers;

use App\Models\JenisInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisInventarisController extends Controller
{
    /**
     * Fungsi untuk menampilkan daftar jenis inventaris
     */
    public function index()
    {
        $data = JenisInventaris::all();
        return view('jenis_inventaris.index', compact('data'));
    }

    /**
     * Fungsi untuk menampilkan halaman tambah jenis inventaris
     */
    public function tambah()
    {
        return view('jenis_inventaris.tambah');
    }

    /**
     * Fungsi untuk menyimpan jenis inventaris baru
     */
    public function simpan(Request $request)
    {
        $validasi = $request->validate([
            'nama_jenis' => 'required|unique:tb_jenis|max:50',
            'keterangan' => 'nullable|max:100',
        ], [
            'nama_jenis.required' => 'Nama jenis inventaris harus diisi.',
            'nama_jenis.unique' => 'Nama jenis inventaris sudah ada.',
            'nama_jenis.max' => 'Nama jenis inventaris maksimal 50 karakter.',
            'keterangan.max' => 'Keterangan maksimal 100 karakter.',
        ]);

        DB::beginTransaction();

        try {
            JenisInventaris::create($validasi);
            DB::commit();
            return redirect()->route('jenisInventaris.index')->with('success', 'Jenis inventaris berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan jenis inventaris: ' . $e->getMessage()]);
        }
    }

    /**
     * Fungsi untuk menampilkan halaman edit jenis inventaris
     */
    public function edit($id)
    {
        $data = JenisInventaris::findOrFail($id);
        return view('jenis_inventaris.edit', compact('data'));
    }

    /**
     * Fungsi untuk memperbarui jenis inventaris
     */
    public function update(Request $request, $id)
    {
        $jenisInventaris = JenisInventaris::findOrFail($id);

        $validasi = $request->validate([
            'nama_jenis' => 'required|max:50|unique:tb_jenis,nama_jenis,' . $jenisInventaris->id,
            'keterangan' => 'nullable|max:100',
        ], [
            'nama_jenis.required' => 'Nama jenis inventaris harus diisi.',
            'nama_jenis.unique' => 'Nama jenis inventaris sudah ada.',
            'nama_jenis.max' => 'Nama jenis inventaris maksimal 50 karakter.',
            'keterangan.max' => 'Keterangan maksimal 100 karakter.',
        ]);

        DB::beginTransaction();

        try {
            $jenisInventaris->update($validasi);
            DB::commit();
            return redirect()->route('jenisInventaris.index')->with('success', 'Jenis inventaris berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui jenis inventaris: ' . $e->getMessage()]);
        }
    }

    /**
     * Fungsi untuk menghapus jenis inventaris
     */
    public function hapus($id)
    {
        $jenisInventaris = JenisInventaris::findOrFail($id);

        DB::beginTransaction();

        try {
            $jenisInventaris->delete();
            DB::commit();
            return redirect()->route('jenisInventaris.index')->with('success', 'Jenis inventaris berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus jenis inventaris: ' . $e->getMessage()]);
        }
    }
}
