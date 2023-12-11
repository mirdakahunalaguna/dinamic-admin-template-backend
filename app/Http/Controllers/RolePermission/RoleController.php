<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        // Membaca parameter pengurutan dari permintaan HTTP
        $sortDirection = $request->input('sort', 'asc');

        // Validasi nilai parameter untuk memastikan hanya 'asc' atau 'desc' yang diterima
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            return response()->json(['message' => 'Invalid sorting direction'], 400);
        }

        // Menggunakan parameter pengurutan untuk mengatur kueri pengurutan
        $query = Role::orderBy('created_at');

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

        $roles = $query->paginate($length);
        $draw = $request->input('draw');
        // return response()->json(['message' => 'Data roles berhasil ditemukan', 'data' => $roles]);
        return response()->json(['draw' => $draw, 'message' => 'Data role berhasil ditemukan', 'data' => $roles]);
    }
    public function update(Request $request, $id)
    {
        try {
            // Cari objek role berdasarkan ID
            $role = Role::find($id);

            if (!$role) {
                return response()->json(['message' => 'Data role tidak ditemukan'], 404);
            }

            // Validasi input dari request
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Memulai transaksi database
            DB::beginTransaction();

            // Update data role
            $role->name = $request->input('name');
            $role->save();

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json(['message' => 'Data role berhasil diperbarui', 'data' => $role]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return response()->json(['message' => 'Gagal memperbarui data role', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission = Permission::get();

        return $this->sendResponse($permission, 'Successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
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

            $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

            // Commit the transaction
            DB::commit();

            return $this->sendResponse($role, 'Role created successfully', 200);
        } catch (Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollback();

            return $this->sendError(null, 'Error: ' . $e->getMessage(), 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        ->where("role_has_permissions.role_id",$id)
        ->get();

        $data['roles'] = $role;
        $data['permissions'] = $rolePermissions;

        return $this->sendResponse($data, 'Success', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);

        $permission = Permission::get();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();

        $data['role'] = $role;
        $data['permission'] = $permission;
        $data['rolePermission'] = $rolePermissions;

        return $this->sendResponse($data, 'Success', 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        if($role){
            $role->delete();

            return $this->sendResponse(null, 'Successfully', 200);
        }

        return $this->sendError(null, 'Error Delete', 404);
    }
}
