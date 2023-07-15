<?php

namespace App\Http\Controllers\Presensi;

use Throwable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data masukan
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i:s',
            'jam_keluar' => 'nullable|date_format:H:i:s',
        ]);

        // Periksa apakah sudah ada absensi untuk pengguna pada tanggal yang sama
        $existingAbsensi = Absensi::where('user_id', $validatedData['user_id'])
            ->whereDate('tanggal', $validatedData['tanggal'])
            ->first();

        if ($existingAbsensi) {
            return response()->json([
                'message' => 'Anda sudah melakukan absensi pada hari ini',
                'data' => $existingAbsensi
            ], 422);
        }

        // Buat entri baru dalam tabel absensi
        $absensi = Absensi::create($validatedData);

        if ($absensi) {
            return response()->json([
                'message' => 'Data absensi berhasil disimpan',
                'data' => $absensi
            ], 200);
        } else {
            return response()->json([
                'message' => 'Gagal menyimpan data absensi'
            ], 500);
        }
    }
    public function update(Request $request, $user_id, $tanggal)
    {
        // Validasi data masukan
        $validate =  Validator::make($request->all(), [
            'jam_keluar' => ['required'],
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        $absensi = Absensi::where('user_id', $user_id)->where('tanggal', $tanggal)->first();

        if ($absensi) {
            try {
                // Update data absensi
                $absensi->user_id = $request->user_id;
                $absensi->tanggal = $request->tanggal;
                $absensi->jam_keluar = $request->jam_keluar;

                // Simpan perubahan
                $absensi->save();

                return response()->json(['message' => 'Absensi updated successfully'], 200);
            } catch (Throwable $e) {
                return response()->json(['error' => 'Failed to update absensi', 'message' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['message' => 'Data absensi tidak ditemukan'], 404);
        }
    }

    public function index(Request $request)
    {
        $columns = ['id','tanggal','nama'];
        $column = $request->input('column');
        $length = $request->input('length');
        $dir = $request->input('dir');
        $searchValue = $request->input('search');

        // Membuat query builder untuk Absensi
          $query = Absensi::select('id','tanggal', 'jam_masuk', 'jam_keluar','user_id')->with(['user.pegawai:user_id,nama']);

            if ($searchValue) {
                $query->where(function($query) use ($searchValue) {
                    $query->where('tanggal', 'like', '%'. $searchValue .'%')
                        ->orWhere('jam_masuk', 'like', '%'. $searchValue .'%')
                        ->orWhere('jam_keluar', 'like', '%'. $searchValue .'%')
                        ->orWhereHas('user', function ($query) use ($searchValue) {
                            $query->whereHas('pegawai', function ($query) use ($searchValue) {
                                $query->where('nama', 'like', '%'. $searchValue .'%');
                            });
                        });
                });
            }
        // Lakukan sorting pada data
        $query->orderBy($columns[$column], $dir);
        //lakukan paginasi pada data
        $absensis = $query->paginate($length);
        return [
            'data' => $absensis,
            'draw' => $request->input('draw'),
        ];
    }

       /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $absensi = Absensi::find($id);

        if ($absensi) {
            return response()->json(['message' => 'Data absensi berhasil ditemukan', 'data' => $absensi]);
        } else {
            return response()->json(['message' => 'Data absensi tidak ditemukan vvv'], 404);
        }
    }

    public function search(Request $request)
    {
        $user_id = $request->input('user_id');
        $tanggal_mulai = date('Y-m-d', strtotime($request->input('tanggal_mulai')));
        $tanggal_akhir = date('Y-m-d', strtotime($request->input('tanggal_akhir')));

        $absensi = Absensi::where('user_id', $user_id)
            ->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])
            ->get();

        return response()->json(['data' => $absensi]);
        if ($absensi->count() > 0) {
            return response()->json(['message' => 'Data absensi berhasil ditemukan', 'data' => $absensi]);
        } else {
            return response()->json(['message' => 'Data absensi tidak ada'], 404);
        }
    }

    public function destroy($id)
    {
        // Cari dan hapus entri absensi berdasarkan ID
        $absensi = Absensi::find($id);

        if (!$absensi) {
            return response()->json(['message' => 'Data absensi tidak ditemukan'], 404);
        }

        $absensi->delete();

        return response()->json(['message' => 'Data absensi berhasil dihapus']);
    }
}
