<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Membaca parameter pengurutan dari permintaan HTTP
        $sortDirection = $request->input('sort', 'asc');

        // Validasi nilai parameter untuk memastikan hanya 'asc' atau 'desc' yang diterima
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            return response()->json(['message' => 'Invalid sorting direction'], 400);
        }

        // Menggunakan parameter pengurutan untuk mengatur kueri pengurutan
        $query = Permission::orderBy('created_at');

        // Membaca parameter pencarian dari permintaan HTTP
        $searchKeyword = $request->input('search');
        // Jika ada kata kunci pencarian, tambahkan kondisi pencarian ke kueri
        if ($searchKeyword) {
            $query->where('name', 'LIKE', "%$searchKeyword%");
        }

        $length = $request->input('length');
        if (!is_numeric($length) || $length <= 0) {
            return response()->json(['message' => 'Invalid length parameter'], 400);
        }

        $permission = $query->paginate($length);
        $draw = $request->input('draw');
        // return response()->json(['message' => 'Data permissions berhasil ditemukan', 'data' => $permission]);
        return response()->json(['draw' => $draw, 'message' => 'Data permission berhasil ditemukan', 'data' => $permission]);
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
                // Start a database transaction
                DB::beginTransaction();

                $request->validate([
                    'name' => 'required',
                    // 'permission' => 'required',
                ]);

                $permission = Permission::create(['name' => $request->name, 'guard_name' => 'web']);

                // Commit the transaction
                DB::commit();

                return $this->sendResponse($permission, 'Permission created successfully', 200);
            } catch (Exception $e) {
                // Rollback the transaction in case of an exception
                DB::rollback();

                return $this->sendError(null, 'Error: ' . $e->getMessage(), 422);
            }
        }

        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            //
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
            try {
                // Cari objek permission berdasarkan ID
                $permission = Permission::find($id);

                if (!$permission) {
                    return response()->json(['message' => 'Data permission tidak ditemukan'], 404);
                }

                // Validasi input dari request
                $request->validate([
                    'name' => 'required|string|max:255',
                ]);

                // Memulai transaksi database
                DB::beginTransaction();

                // Update data permission
                $permission->name = $request->input('name');
                $permission->save();

                // Commit transaksi jika semuanya berhasil
                DB::commit();

                return response()->json(['message' => 'Data permission berhasil diperbarui', 'data' => $permission]);
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                DB::rollback();

                return response()->json(['message' => 'Gagal memperbarui data permission', 'error' => $e->getMessage()], 500);
            }
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy(string $id)
        {
            $permission = Permission::find($id);

            if($permission){
                $permission->delete();

                return $this->sendResponse(null, 'Successfully', 200);
            }

            return $this->sendError(null, 'Error Delete', 404);
        }
    }
