<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
        {
            $user = Auth::user();
            if ($user) {
                // Mengambil peran (roles) pengguna
                $roles = $user->getRoleNames();

                // Mengambil izin (permissions) pengguna
                $permissions = $user->getAllPermissions();

                // Menambahkan peran dan izin ke respons
                $user->roles = $roles;
                $user->permissions = $permissions;

                return response()->json($user, 200);
            } else {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
        }
}

