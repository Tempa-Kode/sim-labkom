<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\JenisInventaris;
use App\Models\RuangLaboratorium;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventarisController extends Controller
{
    /*
    * fungsi untuk menampilkan halaman inventaris
    */
    public function index()
    {
        $data = Inventaris::all();
        return view('inventaris.index', compact('data'));
    }

    /*
    * fungsi untuk menampilkan halaman tambah inventaris
    */
    public function tambah()
    {
        $jenis = JenisInventaris::all();
        $ruangLab = RuangLaboratorium::all();
        return view('inventaris.tambah', compact('jenis', 'ruangLab'));
    }

    /*
    * fungsi untuk menyimpan data inventaris baru
    */
    public function simpan(Request $request)
    {
        $validasi = $request->validate([
            'nama_barang' => 'required|max:50',
            'kode_barang' => 'nullable|unique:tb_inventaris,id',
            'kondisi' => 'required|max:50',
            'keterangan' => 'nullable|max:100',
            'id_jenis' => 'required|max:100',
            'jumlah' => 'required|integer|min:1',
            'id_ruang' => 'required|exists:tb_ruang_lab,id',
        ], [
            'nama_barang.required' => 'Nama barang harus diisi',
            'nama_barang.max' => 'Nama barang maksimal 50 karakter',
            'kode_barang.unique' => 'Kode barang harus unik',
            'kondisi.required' => 'Kondisi harus diisi',
            'kondisi.max' => 'Kondisi maksimal 50 karakter',
            'keterangan.max' => 'Keterangan maksimal 100 karakter',
            'id_jenis.required' => 'Jenis inventaris harus dipilih',
            'id_jenis.max' => 'Jenis inventaris tidak valid',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.integer' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 1',
            'id_ruang.required' => 'Ruang laboratorium harus dipilih',
            'id_ruang.exists' => 'Ruang laboratorium tidak valid',
        ]);

        $validasi['id_pengguna'] = Auth::user()->id;

        DB::beginTransaction();
        try {
            Inventaris::create($validasi);
            DB::commit();
            return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menambahkan inventaris: ' . $e->getMessage()])->withInput();
        }
    }

    /*
    * fungsi untuk menampilkan halaman edit inventaris
    */
    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        if (!$inventaris) {
            return redirect()->route('inventaris.index')->withErrors(['error' => 'Inventaris tidak ditemukan']);
        }

        $jenis = JenisInventaris::all();
        $ruangLab = RuangLaboratorium::all();
        return view('inventaris.edit', compact('inventaris', 'jenis', 'ruangLab'));
    }

    /*
    * fungsi untuk memperbarui data inventaris
    */
    public function update(Request $request, $id)
    {
        $inventaris = Inventaris::findOrFail($id);
        if (!$inventaris) {
            return redirect()->route('inventaris.index')->withErrors(['error' => 'Inventaris tidak ditemukan']);
        }

        $validasi = $request->validate([
            'nama_barang' => 'required|max:50',
            'kode_barang' => 'nullable|unique:tb_inventaris,id,' . $inventaris->id,
            'kondisi' => 'required|max:50',
            'keterangan' => 'nullable|max:100',
            'id_jenis' => 'required|max:100',
            'jumlah' => 'required|integer|min:1',
            'id_ruang' => 'required|exists:tb_ruang_lab,id',
        ], [
            'nama_barang.required' => 'Nama barang harus diisi',
            'nama_barang.max' => 'Nama barang maksimal 50 karakter',
            'kode_barang.unique' => 'Kode barang harus unik',
            'kondisi.required' => 'Kondisi harus diisi',
            'kondisi.max' => 'Kondisi maksimal 50 karakter',
            'keterangan.max' => 'Keterangan maksimal 100 karakter',
            'id_jenis.required' => 'Jenis inventaris harus dipilih',
            'id_jenis.max' => 'Jenis inventaris tidak valid',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.integer' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 1',
            'id_ruang.required' => 'Ruang laboratorium harus dipilih',
            'id_ruang.exists' => 'Ruang laboratorium tidak valid',
        ]);

        $validasi['id_pengguna'] = Auth::user()->id;

        DB::beginTransaction();
        try {
            $inventaris->update($validasi);
            DB::commit();
            return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui inventaris: ' . $e->getMessage()])->withInput();
        }
    }

    /*
    * fungsi untuk menghapus inventaris
    */
    public function hapus($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        if (!$inventaris) {
            return redirect()->route('inventaris.index')->withErrors(['error' => 'Inventaris tidak ditemukan']);
        }
        DB::beginTransaction();
        try {
            $inventaris->delete();
            DB::commit();
            return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus inventaris: ' . $e->getMessage()]);
        }
    }

    /*
     * fungsi untuk mencetak report pdf inventaris
     * */
    public function exportPdf()
    {
        $data = Inventaris::all();
        $pdf = Pdf::loadView('inventaris.exportpdf', compact('data'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    public function exportExcel()
    {
        $data = Inventaris::all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Barang');
        $sheet->setCellValue('C1', 'Kode Barang');
        $sheet->setCellValue('D1', 'Kondisi');
        $sheet->setCellValue('E1', 'Ket');
        $sheet->setCellValue('F1', 'Jenis');
        $sheet->setCellValue('G1', 'Jumlah');
        $sheet->setCellValue('H1', 'Ruangan');

        $row = 2;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_barang);
            $sheet->setCellValue('C' . $row, $item->kode_barang);
            $sheet->setCellValue('D' . $row, $item->kondisi);
            $sheet->setCellValue('E' . $row, $item->keterangan ?? '-');
            $sheet->setCellValue('F' . $row, $item->jenisInventaris->nama_jenis);
            $sheet->setCellValue('G' . $row, $item->jumlah);
            $sheet->setCellValue('H' . $row, $item->ruangLaboratorium->nama_ruang);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'inventaris_lab_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
