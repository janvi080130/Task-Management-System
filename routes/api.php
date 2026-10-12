<?php

use App\Http\Controllers\Api\TaskController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out.']);
    });
    Route::apiResource('tasks', TaskController::class);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
    $user = User::where('email', $credentials['email'])->first();

    if (! $user || ! Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Invalid credentials.'], 422);
    }

    return response()->json(['token' => $user->createToken('task-api')->plainTextToken, 'user' => $user]);
})->middleware('throttle:10,1');
