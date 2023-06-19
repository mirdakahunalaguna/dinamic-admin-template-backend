<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        }
        else if (Auth::attempt(['email'     => $request->email,
                                'password'  => $request->password]))
        {
            $user = Auth::user();
            $response = [];
            $response=$user->createToken('mirdApp')->plainTextToken;
            return response()->json(['data'=>$user,
                                     'token'=>$response,
                                     'status'=>200]);
        } else {
            $response['status'] = false;
            $response['message'] = 'Unauthorized';
            return response()->json(['error' => 'Wrong username or password'], 203);
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

    public function profile()
    {
        $user = Auth::user();
        if($user){
        $user = $user->makeHidden(['email_verified_at', 'password']);

        $response['status'] = true;
        $response['message'] = 'User login profil';
        $response['data'] = $user;

        return response()->json($response, 200);
        }else{
            return response()->json(['error' => 'Logout failled.'],203);
        }

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

    public function me(Request $request){
        $user = Auth::user();
        if($user){
            return response()->json($user, 200);
        }else{
            return response()->json(['error' => 'Unauthenticated']);
        }
    }

}
