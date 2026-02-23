<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'cep' => 'required|digits:8',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'cep' => $validated['cep'],
            'city' => $validated['city'],
            'state' => $validated['state'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);

    }

    public function login(Request $request) {
        $validated= $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        $user = User::where('email', $validated['email'])->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function pictures(Request $request) {
        return response()->json();
    }

    public function logout(Request $request) {
        /** @var PersonalAccessToken|null $token */
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ]);
    }
}
