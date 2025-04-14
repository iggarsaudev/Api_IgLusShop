<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    if ($user->role !== 'admin') {
        $token = $user->createToken('api-token')->plainTextToken;
    }
    else {
        $token = $user->createToken('admin-token')->plainTextToken;
    }

    return response()->json([
        'token' => $token,
        'user' => $user,
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
 