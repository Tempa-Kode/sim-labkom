<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalLaboratorium;
use App\Models\Notifikasi;
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
            ->join('tb_ruang_lab', 'tb_ruang_lab.id', '=', 'tb_jadwal_lab.id_ruang_lab')
            ->orderBy('tb_ruang_lab.nama_ruang', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->select('tb_jadwal_lab.*')
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
            // mulai 00:00..23:59
            'waktu_mulai' => ['required','regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            // selesai 00:00..24:00 (24:00 diartikan akhir hari)
            'waktu_selesai' => ['required','regex:/^(?:[01]\d|2[0-3]|24):[0-5]\d$/'],
            'id_dosen' => 'required|exists:tb_dosen,id',
            'status_ruang' => 'required|string|max:20',
        ], [
            'id_ruang_lab.required' => 'Ruang lab harus dipilih.',
            'hari.required' => 'Hari harus diisi.',
            'waktu_mulai.required' => 'Waktu mulai harus diisi.',
            'waktu_selesai.required' => 'Waktu selesai harus diisi.',
            'waktu_mulai.regex' => 'Format waktu mulai tidak valid.',
            'waktu_selesai.regex' => 'Format waktu selesai tidak valid.',
            'id_dosen.required' => 'Dosen harus dipilih.',
            'status_ruang.required' => 'Status ruang harus dipilih.',
        ]);

        // Cek bentrok jadwal (overlap) pada ruang dan hari yang sama
        $hasConflict = JadwalLaboratorium::where('id_ruang_lab', $request->id_ruang_lab)
            ->where('hari', $request->hari)
            ->where(function ($q) use ($request) {
                $q->where('waktu_mulai', '<', $request->waktu_selesai)
                  ->where('waktu_selesai', '>', $request->waktu_mulai);
            })
            ->exists();
        if ($hasConflict) {
            return redirect()->back()->withErrors(['error' => 'Jadwal bentrok dengan jadwal lain pada ruang dan hari yang sama.'])->withInput();
        }

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
        // Cek bentrok jadwal (overlap) saat update, kecuali diri sendiri
        $hasConflict = JadwalLaboratorium::where('id_ruang_lab', $request->id_ruang_lab)
            ->where('hari', $request->hari)
            ->where('id', '!=', $jadwal->id)
            ->where(function ($q) use ($request) {
                $q->where('waktu_mulai', '<', $request->waktu_selesai)
                  ->where('waktu_selesai', '>', $request->waktu_mulai);
            })
            ->exists();
        if ($hasConflict) {
            return redirect()->back()->withErrors(['error' => 'Jadwal bentrok dengan jadwal lain pada ruang dan hari yang sama.'])->withInput();
        }

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

    /**
     * fungsi untuk export data jadwal lab ke excel
     */
    public function exportExcel()
    {
        $data = JadwalLaboratorium::with(['ruangLaboratorium', 'dosen'])
            ->orderBy('hari')
            ->orderBy('waktu_mulai')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Hari');
        $sheet->setCellValue('C1', 'Waktu Mulai');
        $sheet->setCellValue('D1', 'Waktu Selesai');
        $sheet->setCellValue('E1', 'Ruang Laboratorium');
        $sheet->setCellValue('F1', 'Dosen');

        $row = 2;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->hari);
            $sheet->setCellValue('C' . $row, $item->waktu_mulai);
            $sheet->setCellValue('D' . $row, $item->waktu_selesai);
            $sheet->setCellValue('E' . $row, $item->ruangLaboratorium->nama_ruang ?? '-');
            $sheet->setCellValue('F' . $row, $item->dosen->nama_dosen ?? '-');
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'jadwal_lab_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
    * fungsi untuk menampilkan jadwal lab di halaman laboratorium
    */
    public function jadwalLaboratorium(Request $request){
        $hari = $request->input('hari') ?? \Carbon\Carbon::now()->locale('id')->translatedFormat('l');
        $waktu = $request->input('waktu'); // format: 'H:i'

        $tanggalHariIni = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y');


        $dataJadwal = JadwalLaboratorium::with(['ruangLaboratorium', 'dosen', 'dosen.user'])
        ->join('tb_ruang_lab', 'tb_ruang_lab.id', '=', 'tb_jadwal_lab.id_ruang_lab')
        // ->orderBy('tb_ruang_lab.nama_ruang', 'asc')
        ->orderBy('waktu_mulai', 'asc')
        ->select('tb_jadwal_lab.*')
        ->filterHari($hari)
        ->filterWaktu($waktu)
        ->get();
        return view('home.laboratorium', compact('dataJadwal', 'tanggalHariIni', 'hari', 'waktu'));
    }

    /**
     * Ubah status ruang (digunakan/kosong) dan opsional buat notifikasi jika batal
     */
    public function ubahStatus(Request $request, $id)
    {
        $request->validate([
            'status_ruang' => 'required|in:digunakan,kosong',
        ]);
        $jadwal = JadwalLaboratorium::findOrFail($id);
        $jadwal->status_ruang = $request->status_ruang;
        $jadwal->save();

        // Jika ada alasan batal, buat notifikasi
        if ($request->status_ruang === 'kosong' && $request->filled('alasan')) {
            Notifikasi::create([
                'jadwal_id' => $jadwal->id,
                'judul' => 'Batal Pakai',
                'pesan' => $request->input('alasan'),
                'status' => 'baru',
            ]);
        }
        return back()->with('success', 'Status ruang diperbarui.');
    }

    /**
     * Dipanggil saat waktu habis untuk kirim notifikasi otomatis
     */
    public function notifySelesai(Request $request, $id)
    {
        $jadwal = JadwalLaboratorium::with(['ruangLaboratorium','dosen'])->findOrFail($id);
            // Ubah status jadi kosong
            $jadwal->status_ruang = 'kosong';
            $jadwal->save();

            // Buat notifikasi
            Notifikasi::create([
                'jadwal_id' => $jadwal->id,
                'judul' => 'Waktu Habis',
                'pesan' => 'Sesi di '.$jadwal->ruangLaboratorium->nama_ruang.' ('.$jadwal->hari.' '.$jadwal->waktu_mulai.'-'.$jadwal->waktu_selesai.') telah selesai. Silakan konfirmasi jadwal berikutnya.',
                'status' => 'baru',
            ]);
            return response()->json(['ok' => true, 'status' => $jadwal->status_ruang]);
    }
}
