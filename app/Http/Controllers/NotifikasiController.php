<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\JadwalLaboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    public function index()
    {
    $data = Notifikasi::with(['jadwal', 'jadwal.ruangLaboratorium', 'jadwal.dosen', 'pengajuan', 'pengajuan.ruang', 'pengajuan.dosen'])
            ->orderByDesc('id')
            ->paginate(20);
        return view('notifikasi.index', compact('data'));
    }

    public function create()
    {
        $jadwal = JadwalLaboratorium::with(['ruangLaboratorium','dosen'])->orderBy('hari')->orderBy('waktu_mulai')->get();
        return view('notifikasi.tambah', compact('jadwal'));
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'jadwal_id' => 'required|exists:tb_jadwal_lab,id',
            'judul' => 'required|string|max:100',
            'pesan' => 'required|string|max:255',
        ]);
        $valid['status'] = 'baru';
        Notifikasi::create($valid);
        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi dibuat.');
    }

    public function markRead(Notifikasi $notifikasi)
    {
        $notifikasi->update(['status' => 'dibaca']);
        return back()->with('success', 'Notifikasi ditandai dibaca.');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();
        return back()->with('success', 'Notifikasi dihapus.');
    }

    /**
     * Konfirmasi pengajuan: buat entri JadwalLaboratorium dari pengajuan.
     */
    public function konfirmasiPengajuan(Request $request, Notifikasi $notifikasi)
    {
        $pengajuan = $notifikasi->pengajuan()->with(['ruang','dosen'])->first();
        if (!$pengajuan) {
            return back()->withErrors(['error' => 'Pengajuan tidak ditemukan pada notifikasi ini.']);
        }
        DB::beginTransaction();
        try {
            // tentukan hari dari tanggal_pemakaian (indonesia)
            $hari = strtolower(\Carbon\Carbon::parse($pengajuan->tanggal_pemakaian)->locale('id')->dayName);
            $jadwal = JadwalLaboratorium::create([
                'id_ruang_lab'  => $pengajuan->id_ruang,
                'hari'          => $hari,
                'waktu_mulai'   => $pengajuan->jam_mulai,
                'waktu_selesai' => $pengajuan->jam_selesai,
                'id_dosen'      => $pengajuan->id_dosen,
                'status_ruang'  => 'digunakan',
            ]);

            // update status pengajuan dan notifikasi
            $pengajuan->status = 'disetujui';
            $pengajuan->save();
            $notifikasi->status = 'dibaca';
            $notifikasi->jadwal_id = $jadwal->id;
            $notifikasi->save();

            DB::commit();
            return back()->with('success', 'Pengajuan dikonfirmasi dan jadwal dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengonfirmasi pengajuan: '.$e->getMessage()]);
        }
    }
}
