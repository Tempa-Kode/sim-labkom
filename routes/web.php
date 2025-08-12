<?php

use App\Http\Controllers\ProfilController;
use App\Models\JadwalLaboratorium;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () { return view('home.beranda'); })->name('home');
Route::get('/tentang', function () { return view('home.tentang'); })->name('tentang');
Route::get('/laboratorium', [App\Http\Controllers\JadwalLabController::class, 'jadwalLaboratorium'])->name('laboratorium');
Route::get('/kotak', function () { return view('home.kontak'); })->name('kontak');

Route::get('/login', function () { return view('login'); })->name('login');
Route::post('/prosesLogin', [App\Http\Controllers\AutentikasiController::class, 'prosesLogin'])->name('prosesLogin');
Route::post('/logout', [App\Http\Controllers\AutentikasiController::class, 'logout'])->name('logout');

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/updateFoto', [ProfilController::class, 'updateFoto'])->name('profil.updateFoto');
    Route::get('/profil/ubah-password', [ProfilController::class, 'ubahPassword'])->name('profil.ubahPassword');
    Route::put('/profil/ubah-password', [ProfilController::class, 'updatePassword'])->name('profil.updatePassword');

    Route::prefix('/pengguna')->group(function () {
        Route::get('/', [App\Http\Controllers\PenggunaController::class, 'index'])->name('pengguna.index');
        Route::delete('/{user:id}', [App\Http\Controllers\PenggunaController::class, 'hapus'])->name('pengguna.hapus');
        Route::post('/tambah', [App\Http\Controllers\PenggunaController::class, 'tambah'])->name('pengguna.tambah');
        Route::get('/edit/{user:id}', [App\Http\Controllers\PenggunaController::class, 'edit'])->name('pengguna.edit');
        Route::put('/update/{user:id}', [App\Http\Controllers\PenggunaController::class, 'update'])->name('pengguna.update');
        Route::get('/export-pdf-dosen', [App\Http\Controllers\PenggunaController::class, 'exportPdfDosen'])->name('pengguna.exportPdfDosen');
    });

    Route::prefix('/ruang-lab')->group(function () {
        Route::get('/', [App\Http\Controllers\RuangLabController::class, 'index'])->name('ruangLab.index');
        Route::get('/tambah', [App\Http\Controllers\RuangLabController::class, 'tambah'])->name('ruangLab.tambah');
        Route::post('/simpan', [App\Http\Controllers\RuangLabController::class, 'simpan'])->name('ruangLab.simpan');
        Route::get('/edit/{ruangLab:id}', [App\Http\Controllers\RuangLabController::class, 'edit'])->name('ruangLab.edit');
        Route::put('/update/{ruangLab:id}', [App\Http\Controllers\RuangLabController::class, 'update'])->name('ruangLab.update');
        Route::delete('/{ruangLab:id}', [App\Http\Controllers\RuangLabController::class, 'hapus'])->name('ruangLab.hapus');
    });

    Route::prefix('/jadwal-lab')->group(function () {
        Route::get('/', [App\Http\Controllers\JadwalLabController::class, 'index'])->name('jadwalLab.index');
        Route::get('/tambah', [App\Http\Controllers\JadwalLabController::class, 'tambah'])->name('jadwalLab.tambah');
        Route::post('/simpan', [App\Http\Controllers\JadwalLabController::class, 'simpan'])->name('jadwalLab.simpan');
        Route::get('/edit/{id}', [App\Http\Controllers\JadwalLabController::class, 'edit'])->name('jadwalLab.edit');
        Route::put('/update/{id}', [App\Http\Controllers\JadwalLabController::class, 'update'])->name('jadwalLab.update');
        Route::delete('/{id}', [App\Http\Controllers\JadwalLabController::class, 'hapus'])->name('jadwalLab.hapus');
        Route::get('/export-pdf', [App\Http\Controllers\JadwalLabController::class, 'exportPdf'])->name('jadwalLab.exportPdf');
        Route::get('/export-excel', [App\Http\Controllers\JadwalLabController::class, 'exportExcel'])->name('jadwalLab.exportExcel');
    });

    Route::prefix('/jenis-inventaris')->group(function () {
        Route::get('/', [App\Http\Controllers\JenisInventarisController::class, 'index'])->name('jenisInventaris.index');
        Route::get('/tambah', [App\Http\Controllers\JenisInventarisController::class, 'tambah'])->name('jenisInventaris.tambah');
        Route::post('/simpan', [App\Http\Controllers\JenisInventarisController::class, 'simpan'])->name('jenisInventaris.simpan');
        Route::get('/edit/{id}', [App\Http\Controllers\JenisInventarisController::class, 'edit'])->name('jenisInventaris.edit');
        Route::put('/update/{id}', [App\Http\Controllers\JenisInventarisController::class, 'update'])->name('jenisInventaris.update');
        Route::delete('/{id}', [App\Http\Controllers\JenisInventarisController::class, 'hapus'])->name('jenisInventaris.hapus');
    });

    Route::prefix('/inventaris')->group(function () {
        Route::get('/', [App\Http\Controllers\InventarisController::class, 'index'])->name('inventaris.index');
        Route::get('/tambah', [App\Http\Controllers\InventarisController::class, 'tambah'])->name('inventaris.tambah');
        Route::post('/simpan', [App\Http\Controllers\InventarisController::class, 'simpan'])->name('inventaris.simpan');
        Route::get('/edit/{id}', [App\Http\Controllers\InventarisController::class, 'edit'])->name('inventaris.edit');
        Route::put('/update/{id}', [App\Http\Controllers\InventarisController::class, 'update'])->name('inventaris.update');
        Route::delete('/{id}', [App\Http\Controllers\InventarisController::class, 'hapus'])->name('inventaris.hapus');
        Route::get('/export-pdf', [App\Http\Controllers\InventarisController::class, 'exportPdf'])->name('inventaris.exportPdf');
        Route::get('/export-excel', [App\Http\Controllers\InventarisController::class, 'exportExcel'])->name('inventaris.exportExcel');
    });

    Route::prefix('/absensi')->group(function () {
        Route::get('/', [App\Http\Controllers\AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/riwayat-absensi-saya', [App\Http\Controllers\AbsensiController::class, 'riwayatAbsensi'])->name('absensi.riwayat');
        Route::post('/absensi', [App\Http\Controllers\AbsensiController::class, 'absensi'])->name('absensi.absensi');
        Route::delete('/hapus/{id}', [App\Http\Controllers\AbsensiController::class, 'hapus'])->name('absensi.hapus');
    });

    Route::prefix('/pengajuan')->group(function () {
        Route::get('/', [App\Http\Controllers\PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/tambah', [App\Http\Controllers\PengajuanController::class, 'tambah'])->name('pengajuan.tambah');
        Route::post('/simpan', [App\Http\Controllers\PengajuanController::class, 'simpan'])->name('pengajuan.simpan');
        Route::put('/setujui/{id}', [App\Http\Controllers\PengajuanController::class, 'setujui'])->name('pengajuan.setujui');
        Route::put('/tolak/{id}', [App\Http\Controllers\PengajuanController::class, 'tolak'])->name('pengajuan.tolak');
        Route::put('/batalkan/{id}', [App\Http\Controllers\PengajuanController::class, 'batalkan'])->name('pengajuan.batalkan');
        Route::get('/keterangan/{id}', [App\Http\Controllers\PengajuanController::class, 'keterangan'])->name('pengajuan.keterangan');
    });
});
