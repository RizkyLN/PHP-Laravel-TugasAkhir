<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ok', function(){
    return view("hallo");
});


use App\Http\Controllers\MahasiswaController;

Route::get('/mahasiswa', [MahasiswaController::class, 'index']);


use App\Http\Controllers\DataMiningController;

Route::get('/dataminer', [DataMiningController::class, 'dataminer']);
