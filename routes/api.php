<?php

use Illuminate\Http\Request;
use App\Http\Controllers\MeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Presensi\AbsensiController;
use App\Http\Controllers\Pegawai\PegawaiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register']);
Route::get('logout', [AuthController::class, 'logout']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('me', MeController::class);
    Route::get('user', [AuthController::class, 'profile']);
    Route::get('logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'absensi'], function () {
    Route::get('/', [AbsensiController::class, 'index']);
    Route::post('/', [AbsensiController::class, 'store']);
    Route::get('/{id}', [AbsensiController::class, 'show']);
    Route::put('/{user_id}/{tanggal}', [AbsensiController::class, 'update']);
    Route::delete('/{id}', [AbsensiController::class, 'destroy']);
    });
    Route::group(['prefix' => 'pegawai'], function () {
    Route::get('/', [PegawaiController::class, 'index']);
    Route::post('/', [PegawaiController::class, 'store']);
    Route::get('/{id}', [PegawaiController::class, 'show']);
    Route::put('/{id}', [PegawaiController::class, 'update']);
    Route::delete('/{id}', [PegawaiController::class, 'destroy']);
    });
});
