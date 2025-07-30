<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenggunaController extends Controller
{
    /**
     * menampilkan halaman dan data pengguna
     */
    public function index()
    {
        $data = User::all();
        return view('pengguna.index', compact('data'));
    }

    /**
     * fungsi untuk menambahkan data pengguna baru oleh admin
     */
    public function tambah(Request $request)
    {
        $validasi = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|unique:tb_pengguna,username',
            'hak_akses' => 'required|in:admin,aslab,dosen',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nama.required' => 'Nama pengguna harus diisi',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'hak_akses.required' => 'Hak akses harus dipilih',
            'hak_akses.in' => 'Hak akses tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        DB::beginTransaction();
        try {
            $pengguna = User::create([
                'nama' => $validasi['nama'],
                'username' => $validasi['username'],
                'hak_akses' => $validasi['hak_akses'],
                'password' => bcrypt($validasi['password']),
            ]);

            if($validasi['hak_akses'] === 'dosen') {
                $dosen = Dosen::select('nama_dosen')->get();
                $found = false;
                $dosen->each(function ($item) use ($validasi, $pengguna, &$found) {
                    if (Str::of($item->nama_dosen)->explode(' ')->first() === Str::of($validasi['nama'])->explode(' ')->first()) {
                        Dosen::where('nama_dosen', $item->nama_dosen)->update([
                            'id_pengguna' => $pengguna->id,
                        ]);
                        $found = true;
                    }
                });

                if (!$found) {
                    Dosen::create([
                        'id_pengguna' => $pengguna->id,
                        'nama_dosen' => $validasi['nama'],
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('pengguna.index')->with('success', 'Akun pengguna berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pengguna.index')->with('error', 'Akun pengguna gagal ditambahkan');
        }
    }

    /**
     * menghapus data pengguna
     */
    public function hapus(User $user)
    {
        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return redirect()->route('pengguna.index')->with('success', "Akun {$user->nama} berhasil dihapus");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pengguna.index')->with('error', 'Akun gagal dihapus');
        }
    }

    /**
     * fungsi untuk menampilkan halaman ubah data pengguna
     */
    public function edit(User $user)
    {
        return view('pengguna.edit', compact('user'));
    }

    /**
     * fungsi untuk memperbaharui data pengguna
     */
    public function update(Request $request, User $user)
    {
        $validasi = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|unique:tb_pengguna,username,' . $user->id,
            'hak_akses' => 'required|in:admin,aslab,dosen',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'nama.required' => 'Nama pengguna harus diisi',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'hak_akses.required' => 'Hak akses harus dipilih',
            'hak_akses.in' => 'Hak akses tidak valid',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        DB::beginTransaction();
        try {
            if ($request->filled('password')) {
                $validasi['password'] = bcrypt($validasi['password']);
            } else {
                unset($validasi['password']);
            }
            $user->update($validasi);

            if($validasi['hak_akses'] == 'dosen') {
                $dosen = Dosen::where('id_pengguna', $user->id)->first();
                if ($dosen) {
                    $dosen->update([
                        'nama_dosen' => $validasi['nama'],
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('pengguna.index')->with('success', "Akun {$user->nama} berhasil diperbaharui");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pengguna.index')->with('error', 'Akun gagal diperbaharui: ' . $e->getMessage());
        }
    }
}
