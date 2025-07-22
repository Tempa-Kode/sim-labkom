<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    /**
     * Fungsi untuk menampilkan halaman profil
     */
    public function index()
    {
        return view('profil.index');
    }

    /**
     * Fungsi untuk memperbarui profil pengguna
     */
    public function update(Request $request)
    {
        $validasi = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50',
        ], [
            'nama.required' => 'Nama harus diisi',
            'nama.max' => 'Nama tidak boleh lebih dari 100 karakter',
            'username.required' => 'Username harus diisi',
            'username.max' => 'Username tidak boleh lebih dari 50 karakter',
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $user->nama = $validasi['nama'];
            $user->username = $validasi['username'];
            $user->save();

            DB::commit();
            return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui profil.']);
        }
    }

    /**
     * Fungsi untuk memperbarui foto profil pengguna
     */
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'foto.required' => 'Foto harus diunggah',
            'foto.image' => 'File yang diunggah harus berupa gambar',
            'foto.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, gif',
            'foto.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            if ($request->hasFile('foto')) {
                $filename = $request->file('foto')->hashName();
                $request->file('foto')->move(public_path('foto-pengguna'), $filename);
                $user->foto = '/foto-pengguna/' . $filename;
                $user->save();
            }

            DB::commit();
            return redirect()->route('profil.index')->with('success', 'Foto profil berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui foto profil.']);
        }
    }

    /**
     * Fungsi untuk menampilkan halaman untuk mengubah password
     */
    public function ubahPassword()
    {
        return view('profil.ubah-password');
    }

    /**
     * Fungsi untuk memperbarui password pengguna
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_lama' => 'required|string|current_password|min:8',
        ], [
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password_lama.current_password' => 'Password lama tidak cocok',
            'password_lama.required' => 'Password lama harus diisi',
            'password_lama.min' => 'Password lama minimal 8 karakter',
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $user->password = bcrypt($request->input('password'));
            $user->save();

            DB::commit();
            return redirect()->route('profil.index')->with('success', 'Password berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui password.']);
        }
    }
}
