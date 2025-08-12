<?php

namespace App\Http\Controllers;

use App\Models\AbsensiAslab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = AbsensiAslab::query();

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_akhir
            ]);
        }

        elseif ($request->query('tanggal')) {
            $tanggal = $request->query('tanggal');
            $query->where('tanggal', $tanggal);
        }

        $data = $query->get();

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

    public function hapus($id)
    {
        $absensi = AbsensiAslab::find($id);
        if (!$absensi) {
            return redirect()->back()->with('error', 'Data absensi tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $absensi->delete();
            DB::commit();
            return redirect()->back()->with('success', "Data absensi {$absensi->aslab->nama} tanggal {$absensi->tanggal} berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data absensi: ' . $e->getMessage());
        }
    }

    public function exportPdfAbsensi(Request $request)
    {
        $query = AbsensiAslab::with('aslab');

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
            $periode = date('d/m/Y', strtotime($request->tanggal));
        } else {
            $periode = 'Semua Data';
        }

        $dataAbsensi = $query->orderBy('tanggal', 'desc')->get();

        $groupedData = $dataAbsensi->groupBy('tanggal');

        $pdf = Pdf::loadView('absensi.pdf-absensi', compact('groupedData', 'periode'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Absensi_Aslab_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
