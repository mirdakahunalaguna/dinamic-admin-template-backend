<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import facade DB
use App\Models\Setting\Submenu;

class SubmenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // Mendapatkan semua data peegawai
        $query = Submenu::all();

        if ($query) {
            return response()->json(['message' => 'Data menu berhasil ditemukan', 'data' => $query]);
        } else {
            return response()->json(['message' => 'Data menu tidak ditemukan'], 404);
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
        try {
            // Validasi input dari request
            $request->validate([
                'menu_id' => 'required',
                'title' => 'required|string|max:255',
                'to' => 'nullable|string|max:255',
            ]);

            // Memulai transaksi database
            DB::beginTransaction();

            // Buat dan simpan submenu baru dalam satu langkah
            $submenu = Submenu::create([
                'menu_id' => $request->input('menu_id'),
                'title' => $request->input('title'),
                'to' => $request->input('to') ?? 'PageSubmenuNotFound',
            ]);

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json(['message' => 'Data submenu berhasil disimpan', 'data' => $submenu], 201);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return response()->json(['message' => 'Gagal menyimpan data submenu', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request)
{
    // Membaca parameter pengurutan dari permintaan HTTP
    $sortDirection = $request->input('sort', 'desc');

    // Validasi nilai parameter untuk memastikan hanya 'asc' atau 'desc' yang diterima
    if (!in_array($sortDirection, ['asc', 'desc'])) {
        return response()->json(['message' => 'Invalid sorting direction'], 400);
    }

    // Membaca parameter pencarian dari permintaan HTTP
    $searchKeyword = $request->input('search');

    // Buat query builder untuk model Submenu
    $query = Submenu::with('menu')->orderBy('created_at', $sortDirection);

    // Jika ada kata kunci pencarian, tambahkan kondisi pencarian ke kueri
    if ($searchKeyword) {
        $query->where('title', 'LIKE', "%$searchKeyword%");
    }

    // Validasi nilai parameter 'length' (jumlah item per halaman)
    $length = $request->input('length');

    if (!is_numeric($length) || $length <= 0) {
        return response()->json(['message' => 'Invalid length parameter'], 400);
    }

    // Menggunakan paginasi dengan jumlah item per halaman yang sesuai
    $submenus = $query->paginate($length);

    // Ambil nilai draw dari permintaan
    $draw = $request->input('draw');

    // Sertakan nilai draw dalam respons JSON Anda
    return response()->json(['draw' => $draw, 'message' => 'Data menu berhasil ditemukan', 'data' => $submenus]);
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
public function update(Request $request, $id)
    {
        try {
            // Cari objek Submenu berdasarkan ID
           $submenu = Submenu::find($id);

            if (!$submenu) {
                return response()->json(['message' => 'Data submenu tidak ditemukan'], 404);
            }

            // Validasi input dari request
            $request->validate([
                'menu_id' => 'required|integer', // Mengubah menjadi integer
                'title' => 'required|string|max:255',
                'to' => 'nullable|string|max:255',
                'icon' => 'nullable|string|max:255',
            ]);
            // Memulai transaksi database
            DB::beginTransaction();

            // Update data menu
           $submenu->menu_id = $request->input('menu_id');
           $submenu->title = $request->input('title');
           $submenu->to = $request->input('to');
           $submenu->icon = $request->input('icon');
           $submenu->save();

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json(['message' => 'Data menu berhasil diperbarui', 'data' =>$submenu]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return response()->json(['message' => 'Gagal memperbarui data menu', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        // Cari dan hapus entri submenu berdasarkan ID
        $submenu = Submenu::find($id);

        if (!$submenu) {
            return response()->json(['message' => 'Data submenu tidak ditemukan'], 404);
        }

        $submenu->delete();

        return response()->json(['message' => 'Data submenu berhasil dihapus']);
    }
}
