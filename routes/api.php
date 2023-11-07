<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermission\RolePermissionController;
use App\Http\Controllers\Presensi\AbsensiController;
use App\Http\Controllers\Presensi\IjinKehadiranController;
use App\Http\Controllers\Pegawai\PegawaiController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Setting\SubmenuController;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('me', [MeController::class, 'index']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'profile']);

        Route::prefix('menu')->group(function () {
                Route::get('', [MenuController::class, 'index']);
            });

        Route::prefix('submenu')->group(function () {
            Route::get('', [SubmenuController::class, 'index']);
        });

    Route::middleware(['role:Manajer Sumber Daya Manusia'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::resource('roles', RoleController::class);

        Route::prefix('absensi')->group(function () {
            Route::get('', [AbsensiController::class, 'index']);
            Route::get('search', [AbsensiController::class, 'search']);
            Route::get('{id}', [AbsensiController::class, 'show']);
            Route::post('', [AbsensiController::class, 'store']);
            Route::put('{user_id}/{tanggal}', [AbsensiController::class, 'update']);
            Route::delete('{id}', [AbsensiController::class, 'destroy']);
        });

        Route::prefix('pegawai')->group(function () {
            Route::get('', [PegawaiController::class, 'index']);
            Route::post('', [PegawaiController::class, 'store']);
            Route::get('{id}', [PegawaiController::class, 'show']);
            Route::put('{id}', [PegawaiController::class, 'update']);
            Route::delete('{id}', [PegawaiController::class, 'destroy']);
        });

        Route::prefix('ijinKehadiran')->group(function () {
            Route::get('', [IjinKehadiranController::class, 'index']);
            Route::post('', [IjinKehadiranController::class, 'store']);
            Route::get('{id}', [IjinKehadiranController::class, 'show']);
            Route::put('{id}', [IjinKehadiranController::class, 'update']);
            Route::delete('{id}', [IjinKehadiranController::class, 'destroy']);
        });
    });
    Route::middleware(['role:super admin'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::resource('roles', RoleController::class);
         //SUPER ADMIN DAPAT MANIPULASI DATA MENU
        Route::prefix('menu')->group(function () {
                // Route::get('', [MenuController::class, 'index']);
                Route::post('', [MenuController::class, 'store']);
                Route::get('{id}', [MenuController::class, 'show']);
                Route::put('{id}', [MenuController::class, 'update']);
                Route::delete('{id}', [MenuController::class, 'destroy']);
                    // Tambahkan rute API baru untuk menu_role
                Route::post('menu-role', [MenuController::class, 'createMenuRole']);
                // Rute untuk mengedit entri menu_role berdasarkan ID
                Route::put('menu-role/{id}', [MenuController::class, 'editMenuRole']);
                Route::delete('menu-role/{id}', [MenuController::class, 'deleteMenuRole']);
                Route::post('get-menu-role', [MenuController::class, 'getMenuRoles']);
                Route::post('set-menu-role', [MenuController::class, 'setMenuRoles']);
            });
        //SUPER ADMIN DAPAT MANIPULASI DATA SUBMENU
        Route::prefix('submenu')->group(function () {
            // Route::get('', [SubmenuController::class, 'index']);
            Route::post('', [SubmenuController::class, 'store']);
            Route::get('{id}', [SubmenuController::class, 'show']);
            Route::put('{id}', [SubmenuController::class, 'update']);
            Route::delete('{id}', [SubmenuController::class, 'destroy']);
        });

        Route::prefix('roles')->group(function () {
            Route::get('', [RoleController::class, 'index']);
            Route::post('', [RoleController::class, 'store']);
            Route::get('{id}', [RoleController::class, 'show']);
            Route::put('{id}', [RoleController::class, 'update']);
            Route::delete('{id}', [RoleController::class, 'destroy']);
        });

        Route::prefix('role-permission')->group(function () {
            Route::get('user-pegawai', [ UserController::class, 'getUserpegawai']);
            Route::get('user-role-pegawai', [ UserController::class, 'getUserRolePegawai']);
            Route::get('user-roles', [ UserController::class, 'getAllUsersWithRoles']);
            Route::get('roles', [RolePermissionController::class, 'getAllRole']);
            Route::get('permissions', [RolePermissionController::class, 'getAllPermission']);
            Route::post('set-role-user', [RolePermissionController::class, 'setRoleUser']);
            Route::post('update-role-user', [RolePermissionController::class, 'updateRoleUser']);
            Route::delete('delete-role-user/{id}', [ RolePermissionController::class, 'destroy']);
            Route::post('get-role-permission', [RolePermissionController::class, 'getRolePermission']);
            Route::post('set-role-permission', [RolePermissionController::class, 'setRolePermission']);
        });
    });
});
