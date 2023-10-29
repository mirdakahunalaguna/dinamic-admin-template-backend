<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $users = User::all();
        $users = User::when($request->search, function($query, $search){
            $query->where('name', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%');
        })->paginate(2);

        $data = UserResource::collection($users)->resource;

        return $this->sendResponse($data, 'Successfully', 200);
    }
    public function getUserpegawai(Request $request)
    {
        $query = User::select('id', 'email')
            ->with(['pegawai' => function ($query) {
                $query->select('user_id', 'nama','nip');
            }]);

        if ($request->has('search')) {
            $query->whereHas('pegawai', function ($subQuery) use ($request) {
                $subQuery->where('nama', 'like', '%' . $request->input('search') . '%');
            });
        }

        $data = $query->get();

        return response()->json(['message' => 'Data berhasil ditemukan', 'data' => $data]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        if($request->profile_photo_path != null){
            $fileName = time().'.'.$request->profile_photo_path->extension();

            $request->profile_photo_path->move(public_path('uploads'), $fileName);

            $data['profile_photo_path'] = $fileName;
        }

        $data['password'] = Hash::make('password');

        $user = User::create($data);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $result = new UserResource($user);

        return $this->sendResponse($result, 'Successfully', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        if($request->profile_photo_path != null){
            $fileName = time().'.'.$request->profile_photo_path->extension();

            $request->profile_photo_path->move(public_path('uploads'), $fileName);

            $data['profile_photo_path'] = $fileName;
        }

        if($request->password != null){
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->sendResponse('', 'Berhasil dihapus',200);
    }
}
