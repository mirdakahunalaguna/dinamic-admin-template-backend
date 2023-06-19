<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\models\User;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // Mendapatkan semua data peegawai
        $pegawai = Pegawai::all();

    if ($pegawai) {
        return response()->json(['message' => 'Data peegawai berhasil ditemukan', 'data' => $pegawai]);
    } else {
        return response()->json(['message' => 'Data peegawai tidak ditemukan'], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data jika diperlukan
        $validatedData = Validator::make($request->all(),[
            'user_id' => 'required|exists:user,id',
            'nik' => 'required|date',
            'nama' => 'required',
            'jabatan' => 'required',
            'phone' => 'required',
        ],
            [ 'required'    =>  'field harus terisi !',
              'unique'      =>  'data sudah terdaftar',
            ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 202);
        }
        // Buat objek Pegawai baru
        $pegawai = Pegawai::create([
        'user_id'       => $request->user_id,
        'nik'           => $request->nik,
        'nama'          => $request->name,
        'jabatan'       => $request->jabatan,
        'phone'         => $request->phone,
        ]);
        if ($pegawai) {
            return response()->json([
                'success' => true,
                'message' => 'data tersimpan !',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'data gagal tersimpan !',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        try {
            $pegawai = Pegawai::with('user')->findOrFail($user_id);

            return response()->json(['data' => $pegawai], 200);
        } catch (Throwable $e) {
            return response()->json(['error' => 'User not found.', 'message' => $e], 202);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
