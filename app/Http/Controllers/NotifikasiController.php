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
        $data = Notifikasi::with(['jadwal', 'jadwal.ruangLaboratorium', 'jadwal.dosen'])
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
}
