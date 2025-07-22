<?php

use App\Http\Controllers\ProfilController;
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

Route::get('/', function () {
    return view('welcome');
});

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
});
