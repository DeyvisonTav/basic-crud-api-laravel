<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
      try {
        $data = $request->validated();
        
        $user = User::create([
          'name' => $data['name'],
          'email' => $data['email'],
          'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([ 
          'user' => UserResource::make($user),
          'access_token' => $token,
          'token_type' => 'Bearer',
        ], 201);
      } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erro ao registrar usuário',
            'error' => $e->getMessage()
        ], 500);
      }
    }

    public function login(AuthRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([ 
            'user' => UserResource::make($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
  
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Deslogado com sucesso',
        ]);
    }
    
    public function refreshToken(Request $request)
    {
        $request->user()->tokens()->delete();

        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Token atualizado com sucesso',
        ]);
    }
}