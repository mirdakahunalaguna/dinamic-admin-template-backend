<?php

namespace App\Http\Controllers\Presensi;

use Throwable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\IjinKehadiran;

class IjinKehadiranController extends Controller
{
    public function index(Request $request)
{
    $columns = ['id','nip', 'tanggal', 'jenis_ijin', 'keterangan'];
    $column = $request->input('column');
    $length = $request->input('length');
    $dir = $request->input('dir');
    $searchValue = $request->input('search');

    $query = IjinKehadiran::select('id', 'nip', 'tanggal', 'jenis_ijin', 'keterangan','status')->with('pegawai:nip,nama');

    if ($searchValue) {
        $query->where(function($query) use ($searchValue) {
            $query->where('nip', 'like', '%'. $searchValue .'%')
                ->orWhere('tanggal', 'like', '%'. $searchValue .'%')
                ->orWhere('jenis_ijin', 'like', '%'. $searchValue .'%')
                ->orWhere('keterangan', 'like', '%'. $searchValue .'%')
                ->orWhereHas('pegawai', function ($query) use ($searchValue) {
                    $query->where('nama', 'like', '%'. $searchValue .'%');
                });
        });
    }

    // Lakukan sorting pada data
    $query->orderBy($columns[$column], $dir);

    // Lakukan paginasi pada data
    $ijinKehadirans = $query->paginate($length);

    return response()->json([
        'data' => $ijinKehadirans,
        'draw' => $request->input('draw')
    ]);
}


   public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'tanggal' => 'required|date',
            'jenis_ijin' => 'required',
            'jam_masuk' => 'nullable|date_format:H:i:s',
            'jam_keluar' => 'nullable|date_format:H:i:s',
            'jam_kembali' => 'nullable|date_format:H:i:s',
            'keterangan' => 'required',

        ],
        [
        'required'    =>  'field harus terisi !',
        'unique'      =>  'data sudah terdaftar',
        ]);

        try {
         $ijinKehadiran = IjinKehadiran::create([
            'nip' => $request->nip,
            'tanggal' => $request->tanggal,
            'jenis_ijin' => $request->jenis_ijin,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
            'jam_kembali' => $request->jam_kembali,
            'keterangan' => $request->keterangan,
            'status' => $request->status ?? 'unresponded', // Set 'unresponded' as the default value if 'status' is not provided
        ]);


            return response()->json([
                'message' => 'Ijin kehadiran berhasil disimpan.',
                'data' => $ijinKehadiran,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan ijin kehadiran.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }


    public function show($id)
    {
        $ijinKehadiran = IjinKehadiran::find($id);

        if (!$ijinKehadiran) {
            return response()->json([
                'message' => 'Ijin kehadiran tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'data' => $ijinKehadiran,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nip' => 'required',
            'tanggal' => 'required|date',
            'jenis_ijin' => 'required',
        ]);

        $ijinKehadiran = IjinKehadiran::find($id);

        if (!$ijinKehadiran) {
            return response()->json([
                'message' => 'Ijin kehadiran tidak ditemukan.',
            ], 404);
        }

        $ijinKehadiran->update([
            'nip' => $request->nip,
            'tanggal' => $request->tanggal,
            'jenis_ijin' => $request->jenis_ijin,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
            'status' => $request->status ?? false,
        ]);

        return response()->json([
            'message' => 'Ijin kehadiran berhasil diperbarui.',
            'data' => $ijinKehadiran,
        ]);
    }

    public function destroy($id)
    {
        $ijinKehadiran = IjinKehadiran::find($id);

        if (!$ijinKehadiran) {
            return response()->json([
                'message' => 'Ijin kehadiran tidak ditemukan.',
            ], 404);
        }

        $ijinKehadiran->delete();

        return response()->json([
            'message' => 'Ijin kehadiran berhasil dihapus.',
        ]);
    }
}
