<?php

namespace App\Http\Controllers;

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
        $roles = Role::orderBy('created_at', $sortDirection)->get();
        // return view('role.index', compact('roles'));

        return response()->json(['message' => 'Data roles berhasil ditemukan', 'data' => $roles]);
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
        try{
            $request->validate([
                'name' => 'required',
                'permission' => 'required'
            ]);

            $role = Role::create(['name' => $request->name, 'guard_name'=> 'web']);

            $role->syncPermissions($request->permission);

            return $this->sendResponse(null, 'Successfully', 200);
        }catch(Exception $e){
            return $this->sendError(null, 'Error '. $e->getMessage(), 422);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'permission' => 'required'
        ]);

        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->permission);

        return $this->sendResponse(null, 'Successfully', 200);

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
