<?php

namespace App\Http\Controllers;

use App\Models\AbsensiAslab;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        if($request->query('tanggal')){
            $tanggal = $request->query('tanggal');
            $data = AbsensiAslab::where('tanggal', $tanggal)->get();
        } else {
            $data = AbsensiAslab::all();
        }

        return view('absensi.index', compact('data'));
    }
}
