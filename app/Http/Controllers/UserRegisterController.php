<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserRegisterController extends Controller
{
    public function store(UserRegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        // Gera token fake igual ao login
        $token = bin2hex(random_bytes(16));
        return response()->json([
            'message' => 'Usuário cadastrado com sucesso!',
            'user' => $user,
            'token' => $token,
        ], Response::HTTP_CREATED);
    }
}
