<?php

use App\Models\Peserta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\String\TruncateMode;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::fallback(function () {
    return "gagal memuat rute yang diminta";
});

Route::resource('peserta', PesertaController::class);
Route::resource('users', UserController::class)->middleware('isAdmin');
Route::post('users-update-role', [UserController::class, 'updateRole'])->name('users.update-role');
Route::resource('bagian', BagianController::class);


// Route::get('/truncate', function () {
//     Peserta::truncate();
// });
