<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalLaboratorium;
use App\Models\Notifikasi;
use App\Models\RekapanJadwalLab;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
            'alasan_kosong' => 'nullable|string|max:500',
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

        // Validasi waktu: tidak bisa membuat jadwal pada hari ini dengan jam yang sudah lewat
        $hariSekarang = strtolower(\Carbon\Carbon::now()->locale('id')->dayName);
        if ($request->hari === $hariSekarang) {
            $jamSekarang = \Carbon\Carbon::now()->format('H:i');
            if ($request->waktu_selesai < $jamSekarang) {
                return redirect()->back()->withErrors([
                    'waktu_mulai' => 'Tidak dapat membuat jadwal pada jam yang sudah lewat untuk hari ini.'
                ])->withInput();
            }
        }

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
            // Tambahkan ID user yang membuat jadwal
            $validasi['dibuat_oleh'] = auth()->id();

            $jadwal = JadwalLaboratorium::create($validasi);

            // Catat ke rekapan
            $this->catatRekapan($jadwal->id, 'tambah', 'Menambahkan jadwal baru');

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
            'alasan_kosong' => 'nullable|string|max:500',
        ], [
            'id_ruang_lab.required' => 'Ruang lab harus dipilih.',
            'hari.required' => 'Hari harus diisi.',
            'waktu_mulai.required' => 'Waktu mulai harus diisi.',
            'waktu_selesai.required' => 'Waktu selesai harus diisi.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
            'id_dosen.required' => 'Dosen harus dipilih.',
            'status_ruang.required' => 'Status ruang harus dipilih.',
        ]);

        // Validasi waktu: tidak bisa mengubah jadwal pada hari ini dengan jam yang sudah lewat
        $hariSekarang = strtolower(\Carbon\Carbon::now()->locale('id')->dayName);
        if ($request->hari === $hariSekarang) {
            $jamSekarang = \Carbon\Carbon::now()->format('H:i');
            if ($request->waktu_selesai < $jamSekarang) {
                return redirect()->back()->withErrors([
                    'waktu_mulai' => 'Tidak dapat mengubah jadwal pada jam yang sudah lewat untuk hari ini.'
                ])->withInput();
            }
        }

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
            $validasi['dibuat_oleh'] = auth()->id();
            $jadwal->update($validasi);

            // Catat ke rekapan
            $this->catatRekapan($jadwal->id, 'edit', 'Mengedit jadwal');

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
            // Catat ke rekapan sebelum dihapus
            $this->catatRekapan($jadwal->id, 'hapus', 'Menghapus jadwal');

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


        $dataJadwal = JadwalLaboratorium::with(['ruangLaboratorium', 'dosen', 'dosen.user', 'pembuatJadwal'])
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
            'alasan_kosong' => 'required_if:status_ruang,kosong|nullable|string|max:500',
        ]);

        $jadwal = JadwalLaboratorium::findOrFail($id);
        $jadwal->dibuat_oleh = auth()->id();
        $jadwal->status_ruang = $request->status_ruang;

        // Update alasan kosong
        if ($request->status_ruang === 'kosong') {
            $jadwal->alasan_kosong = $request->alasan_kosong;
        } else {
            $jadwal->alasan_kosong = null; // Reset jika status bukan kosong
        }

        $jadwal->save();

        // Catat ke rekapan
        $keterangan = $request->status_ruang === 'kosong' ? 'Mengubah status menjadi kosong: ' . $request->alasan_kosong : 'Mengubah status menjadi digunakan';
        $this->catatRekapan($jadwal->id, 'ubah_status', $keterangan);

        // Jika ada alasan batal, buat notifikasi
        if ($request->status_ruang === 'kosong' && $request->filled('alasan_kosong')) {
            Notifikasi::create([
                'jadwal_id' => $jadwal->id,
                'judul' => 'Batal Pakai',
                'pesan' => $request->input('alasan_kosong'),
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

    /**
     * Fungsi untuk menampilkan rekapan jadwal yang dibuat oleh aslab
     */
    public function rekapanSaya(Request $request)
    {
        $userId = Auth::id();
        $query = RekapanJadwalLab::with(['jadwalLab.ruangLaboratorium', 'jadwalLab.dosen'])
            ->byAslab($userId)
            ->orderBy('tanggal_aksi', 'desc')
            ->orderBy('waktu_aksi', 'desc');

        // Filter berdasarkan minggu jika ada
        if ($request->filled('minggu_mulai')) {
            $tanggalMulai = $request->minggu_mulai;
            $query->mingguan($tanggalMulai);
        }

        $dataRekapan = $query->get();

        // Statistik
        $totalAksi = $dataRekapan->count();
        $statistikAksi = $dataRekapan->groupBy('aksi')->map->count();

        return view('jadwal_lab.rekapan_saya', compact('dataRekapan', 'totalAksi', 'statistikAksi'));
    }

    /**
     * Fungsi untuk export PDF rekapan jadwal aslab
     */
    public function exportRekapanPdf(Request $request)
    {
        $userId = Auth::id();
        $aslab = Auth::user();

        $query = RekapanJadwalLab::with(['jadwalLab.ruangLaboratorium', 'jadwalLab.dosen'])
            ->byAslab($userId)
            ->orderBy('tanggal_aksi', 'desc')
            ->orderBy('waktu_aksi', 'desc');

        // Filter berdasarkan minggu jika ada
        $periode = 'Semua Data';
        if ($request->filled('minggu_mulai')) {
            $tanggalMulai = $request->minggu_mulai;
            $tanggalSelesai = Carbon::parse($tanggalMulai)->addDays(6)->format('Y-m-d');
            $query->mingguan($tanggalMulai);
            $periode = Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('d F Y') . ' - ' . Carbon::parse($tanggalSelesai)->locale('id')->translatedFormat('d F Y');
        }

        $dataRekapan = $query->get();
        $totalAksi = $dataRekapan->count();
        $statistikAksi = $dataRekapan->groupBy('aksi')->map->count();

        $pdf = Pdf::loadView('jadwal_lab.rekapan_pdf', compact('dataRekapan', 'totalAksi', 'statistikAksi', 'aslab', 'periode'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Rekapan_Jadwal_Lab_' . $aslab->nama . '_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Helper function untuk mencatat aksi ke tabel rekapan
     */
    private function catatRekapan($jadwalId, $aksi, $keterangan = null)
    {
        RekapanJadwalLab::create([
            'id_aslab' => Auth::id(),
            'id_jadwal_lab' => $jadwalId,
            'aksi' => $aksi,
            'keterangan' => $keterangan,
            'tanggal_aksi' => now()->format('Y-m-d'),
            'waktu_aksi' => now()->format('H:i:s'),
        ]);
    }
}
