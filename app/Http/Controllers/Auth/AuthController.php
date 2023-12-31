<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 202);
        } else {
            // Attempt to retrieve user data from cache based on the email
            $user = Cache::remember('user_' . $request->email, 60, function () use ($request) {
                // Check if the user is authenticated
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    return Auth::user();
                }

                return null;
            });

            if ($user) {
                // User data found in cache, generate and return the token
                $response = $user->createToken('mirdApp')->plainTextToken;

                return response()->json([
                    'data'   => $user,
                    'token'  => $response,
                    'status' => 200,
                ]);
            } else {
                // User data not found in cache or authentication failed
                return response()->json(['error' => 'Wrong username or password'], 203);
            }
        }
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            // 'name'=>['required','exists:pegawais','unique:users'],
            'email' => ['required', 'email'],
            'name'=>['required'],
            'password' => ['required', 'min:8'],
            'confirm_password' => 'required|same:password'
            ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validate->errors()
            ]);
        }

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);
        $response=[];
        $response['status'] = true;
        $response['message'] = 'Berhasil registrasi';
        $response['token'] = $user->createToken('mirdApp')->plainTextToken;

        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {   $user = Auth::user();
        if($user){
            $request->user()->currentAccessToken()->delete();
            $response['status'] = true;
            $response['message'] = 'Berhasil logout';
            return response()->json($response, 200);
        }else{
            return response()->json(['error' => 'Logout failled.'],203);
        }
    }

 public function profile(Request $request)
{
    // Memeriksa apakah pengguna sudah masuk
    $user = $request->user();

    if (!$user) {
        return response()->json(['error' => 'Unauthenticated']);
    }

    // Attempt to retrieve user data from cache
    $userData = Cache::remember('user_' . $user->id, 3600, function () use ($user) {
        // Mengambil data pengguna yang sedang masuk beserta data Pegawai (jika ada)
        $userWithPegawai = User::with('pegawai')->find($user->id);

        return $userWithPegawai;
    });

    if ($userData) {
        if ($userData->pegawai) {
            // Pengguna memiliki relasi dengan Pegawai, buat respons sesuai kebutuhan
            return response()->json([
                'user_name' => $userData->user_name,
                'email' => $userData->email,
                'nama' => $userData->pegawai->nama,
                'nip' => $userData->pegawai->nip,
            ], 200);
        }
        // Pengguna tidak memiliki relasi dengan Pegawai
        return response()->json(['error' => 'Pengguna bukan Pegawai']);
    }

    // If user data is not found, return an error response
    return response()->json(['error' => 'User data not available'], 500);
}



}
