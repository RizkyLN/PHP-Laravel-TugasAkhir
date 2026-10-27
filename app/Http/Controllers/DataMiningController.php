<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataMiningController extends Controller
{
    public function dataminer()
    {
        return view('data-mining');
    }
}
