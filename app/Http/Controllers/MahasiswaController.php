<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index() {
        //Data dummy: sementara di dlm array . Nanti diganti data 
        //dari database (P6).
        $mahasiswa = [
            ["nama" => "Andi", "ipk" => 3.6],
            ["nama" => "Budi", "ipk" => 2.9],
        ];
        // Kirim ke view: cara 1(compact) dan 
        //cara 2 (array) menghasilkan hal yang sama.
        return view('mahasiswa.index', ['mahasiswa' => $mahasiswa]);
        // return view('mahasiswa.index', compact('mahasiswa'));
    }
}
