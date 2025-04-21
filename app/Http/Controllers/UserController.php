<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(
            User::query()
                ->orderBy('name')
                ->simplePaginate(5)
            );
    }

    public function show(User $user)
    {
       return UserResource::make($user);
    }
    
    public function update(User $user, Request $request)
    {
        $user->update($request->all());
        return UserResource::make($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return UserResource::make($user);
    }
}
