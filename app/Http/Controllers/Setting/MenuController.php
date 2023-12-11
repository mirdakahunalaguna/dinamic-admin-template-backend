<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import facade DB
use App\Models\Setting\MenuRole;
use App\Models\Setting\Menu;
use App\Models\Setting\Role;
class MenuController extends Controller
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
        $menus = Menu::with('submenu','roles')
            ->orderBy('created_at', $sortDirection)
            ->get();

        return response()->json(['message' => 'Data menu berhasil ditemukan', 'data' => $menus]);
    }
    public function show(Request $request)
    {
        // Membaca parameter pengurutan dari permintaan HTTP
        $sortDirection = $request->input('sort', 'asc');

        // Validasi nilai parameter untuk memastikan hanya 'asc' atau 'desc' yang diterima
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            return response()->json(['message' => 'Invalid sorting direction'], 400);
        }

        // Membaca parameter pencarian dari permintaan HTTP
        $searchKeyword = $request->input('search');

        // Menggunakan parameter pengurutan dan pencarian untuk mengatur kueri pengurutan dan pencarian
        $query = Menu::with('submenu','roles')
            ->orderBy('title', $sortDirection);

        // Jika ada kata kunci pencarian, tambahkan kondisi pencarian ke kueri
        if ($searchKeyword) {
            $query->where('title', 'LIKE', "%$searchKeyword%");
        }

        // Menggunakan paginasi dengan jumlah item per halaman (misalnya, 10 item per halaman)
        $length = $request->input('length'); // Jumlah item per halaman
        $menus  = $query->paginate($length);

        // Ambil nilai draw dari permintaan
        $draw = $request->input('draw');

        // Sertakan nilai draw dalam respons JSON Anda
        return response()->json(['draw' => $draw, 'message' => 'Data menu berhasil ditemukan', 'data' => $menus]);
    }

    public function store(Request $request)
    {
        try {
            // Validasi input dari request
            $request->validate([
                'title' => 'required|string|max:255',
                'to' => 'nullable|string|max:255',
                'icon' => 'nullable|string|max:255',
                'roles' => 'nullable|array',
            ]);

            // Memulai transaksi database
            DB::beginTransaction();

            // Buat dan simpan menu baru dalam satu langkah
            $menu = Menu::create([
                'title' => $request->input('title'),
                'to' => $request->input('to') ?? 'PageNotFound',
                'icon' => $request->input('icon'),
            ]);

            // Assign a default role if no roles are provided in the request
            $defaultRoleId = 1;
            $roleIds = $request->input('roles', [$defaultRoleId]);

            // Attach roles to the menu
            $roles = Role::find($roleIds);
            $menu->syncRoles($roles);

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json(['message' => 'Data menu berhasil disimpan', 'data' => $menu], 201);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return response()->json(['message' => 'Gagal menyimpan data menu', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Cari objek menu berdasarkan ID
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json(['message' => 'Data menu tidak ditemukan'], 404);
            }

            // Validasi input dari request
            $request->validate([
                'title' => 'required|string|max:255',
                'to' => 'nullable|string|max:255',
                'icon' => 'nullable|string|max:255',
            ]);

            // Memulai transaksi database
            DB::beginTransaction();

            // Update data menu
            $menu->title = $request->input('title');
            $menu->to = $request->input('to');
            $menu->icon = $request->input('icon');
            $menu->save();

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json(['message' => 'Data menu berhasil diperbarui', 'data' => $menu]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return response()->json(['message' => 'Gagal memperbarui data menu', 'error' => $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        // Cari dan hapus entri menu berdasarkan ID
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['message' => 'Data menu tidak ditemukan'], 404);
        }

        $menu->delete();

        return response()->json(['message' => 'Data menu berhasil dihapus']);
    }

    public function createMenuRole(Request $request)
    {
        try {
            // Validasi input dari request
            $request->validate([
                'menu_id' => 'required|integer',
                'role_id' => 'required|integer',
            ]);

            // Memulai transaksi database
            DB::beginTransaction();

            // Buat entri baru di tabel pivot menu_role
            $menuRole = MenuRole::create([
                'menu_id' => $request->input('menu_id'),
                'role_id' => $request->input('role_id'),
            ]);

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json(['message' => 'Menu berhasil ditambahkan ke peran', 'data' =>$menuRole], 201);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return response()->json(['message' => 'Gagal menambahkan menu ke peran', 'error' => $e->getMessage()], 500);
        }
    }
    public function editMenuRole(Request $request, $id)
    {
        try {
            // Cari entri pivot berdasarkan ID
            $menuRole = MenuRole::find($id);
             if (!$menuRole) {
                return response()->json(['message' => 'Data menu role tidak ditemukan'], 404);
            }
            // Validasi input dari request
            $request->validate([
                'menu_id' => 'required|integer',
                'role_id' => 'required|integer',
            ]);

            // Memulai transaksi database
            DB::beginTransaction();

            if (!$menuRole) {
                return response()->json(['message' => 'Entri menu_role tidak ditemukan'], 404);
            }

            // Perbarui menu_id dan role_id entri pivot
            $menuRole->menu_id = $request->input('menu_id');
            $menuRole->role_id = $request->input('role_id');
            $menuRole->save();

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json(['message' => 'Data menu_role berhasil diubah', 'data' => $menuRole], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return response()->json(['message' => 'Gagal mengubah data menu_role', 'error' => $e->getMessage()], 500);
        }
    }
    //METHOD UNTUK MENGAMBIL ROLE YANG DIMILIKI MENU
    public function getMenuRoles(Request $request)
    {
        // Melakukan query untuk mengambil judul menu berdasarkan role_id
        $roleNames = DB::table('menu_role')
            ->select('roles.name','roles.id')
            ->join('roles', 'menu_role.role_id', '=', 'roles.id')
            ->where('menu_id', $request->menu_id)
            ->get();

        return response()->json(['message' => 'Data ditemukan', 'data' => $roleNames]);
    }
    //METHOD UNTUK MENGAMBIL ROLE YANG DIMILIKI MENU
    public function getRoleMenus(Request $request)
    {
        // Melakukan query untuk mengambil judul menu berdasarkan role_id
        $menuNames = DB::table('menu_role')
            ->select('menus.title','menus.id')
            ->join('menus', 'menu_role.menu_id', '=', 'menus.id')
            ->where('role_id', $request->role_id)
            ->get();

        return response()->json(['message' => 'Data ditemukan', 'data' => $menuNames]);
    }
    //FUNGSI INI UNTUK MENGATUR ROLE DARI MENU YANG DIPILIH
    public function setMenuRoles(Request $request)
    {
        // VALIDASI
        $this->validate($request, [
            'menu_id' => 'required|exists:menus,id',
            'roles' => 'array', // Pastikan roles adalah array
        ]);
        // Ambil menu berdasarkan ID
        $menu = Menu::find($request->menu_id);
        if (!$menu) {
            return response()->json(['message' => 'Menu tidak ditemukan'], 404);
        }
        // Ambil daftar peran yang saat ini diberikan untuk menu ini
        $existingRoles = $menu->roles()->pluck('id')->toArray();
        // Bandingkan dengan daftar peran yang dipilih saat ini
        $selectedRoles = $request->roles;
        // Peran yang perlu ditambahkan
        $rolesToAdd = array_diff($selectedRoles, $existingRoles);
        // Peran yang perlu dihapus
        $rolesToRemove = array_diff($existingRoles, $selectedRoles);
        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Tambahkan peran yang perlu ditambahkan
            $menu->roles()->attach($rolesToAdd);

            // Hapus peran yang perlu dihapus
            $menu->roles()->detach($rolesToRemove);
            // Commit transaksi jika semuanya berhasil
            DB::commit();
            return response()->json(['message' => 'Peran menu berhasil diperbarui', 'data' => $menu]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            return response()->json(['message' => 'Gagal memperbarui peran menu', 'error' => $e->getMessage()], 500);
        }
    }
public function setRoleMenus(Request $request)
    {
        // VALIDASI
        $this->validate($request, [
            'role_id' => 'required|exists:roles,id',
            'menus' => 'array', // Pastikan menus adalah array
        ]);

        // Ambil role berdasarkan ID
        $role = Role::find($request->role_id);

        if (!$role) {
            return response()->json(['message' => 'Role tidak ditemukan'], 404);
        }

        // Ambil daftar menu yang saat ini terhubung dengan role ini
        $existingMenus = $role->menus()->pluck('id')->toArray();

        // Bandingkan dengan daftar menu yang dipilih saat ini
        $selectedMenus = $request->menus;

        // Menu yang perlu ditambahkan
        $menusToAdd = array_diff($selectedMenus, $existingMenus);

        // Menu yang perlu dihapus
        $menusToRemove = array_diff($existingMenus, $selectedMenus);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Tambahkan menu yang perlu ditambahkan
            $role->menus()->attach($menusToAdd);

            // Hapus menu yang perlu dihapus
            $role->menus()->detach($menusToRemove);

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json(['message' => 'Role menu berhasil diperbarui', 'data' => $role]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return response()->json(['message' => 'Gagal memperbarui role menu', 'error' => $e->getMessage()], 500);
        }
    }

}
