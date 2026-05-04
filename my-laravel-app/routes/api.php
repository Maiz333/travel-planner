<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'message' => 'Backend works!'
    ]);
});

// REGISTER
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Registered successfully',
        'user' => $user,
        'token' => $token,
    ]);
});

// LOGIN
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid login data'
        ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Logged in successfully',
        'user' => $user,
        'token' => $token,
    ]);
});

// LOGOUT
Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logged out successfully'
    ]);
})->middleware('auth:sanctum');

// =====================
// CITIES
// =====================

Route::get('/cities', function () {
    return DB::table('cities')->get();
});

Route::get('/seed-cities', function () {
    DB::table('cities')->truncate();

    DB::table('cities')->insert([
        ['name' => 'Paris'],
        ['name' => 'London'],
        ['name' => 'Tokyo'],
        ['name' => 'Rome']
    ]);

    return 'Cities reset and added';
});

// =====================
// PLACES
// =====================

Route::get('/places/{city}', function ($cityId) {
    return DB::table('places')->where('city_id', $cityId)->get();
});

Route::get('/seed-places', function () {
    DB::table('places')->truncate();

    DB::table('places')->insert([
        ['name' => 'Hotel Paris', 'type' => 'hotel', 'city_id' => 1],
        ['name' => 'Paris Restaurant', 'type' => 'restaurant', 'city_id' => 1],
        ['name' => 'Louvre Museum', 'type' => 'museum', 'city_id' => 1],

        ['name' => 'London Hotel', 'type' => 'hotel', 'city_id' => 2],
        ['name' => 'London Restaurant', 'type' => 'restaurant', 'city_id' => 2],
        ['name' => 'British Museum', 'type' => 'museum', 'city_id' => 2],

        ['name' => 'Tokyo Hotel', 'type' => 'hotel', 'city_id' => 3],
        ['name' => 'Tokyo Sushi Restaurant', 'type' => 'restaurant', 'city_id' => 3],
        ['name' => 'Tokyo National Museum', 'type' => 'museum', 'city_id' => 3],

        ['name' => 'Rome Hotel', 'type' => 'hotel', 'city_id' => 4],
        ['name' => 'Rome Restaurant', 'type' => 'restaurant', 'city_id' => 4],
        ['name' => 'Colosseum Museum', 'type' => 'museum', 'city_id' => 4],
    ]);

    return 'Places reset and added';
});

// =====================
// SAVE PLAN
// =====================

Route::post('/plans', function (Request $request) {
    DB::table('plans')->insert([
        'user_id' => $request->user()->id,
        'title' => $request->title,
        'places' => json_encode($request->places),
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return ['message' => 'Trip plan saved'];
})->middleware('auth:sanctum');

// =====================
// GET MY PLANS
// =====================

Route::get('/my-plans', function (Request $request) {
    return DB::table('plans')
        ->where('user_id', $request->user()->id)
        ->latest()
        ->get();
})->middleware('auth:sanctum');

// =====================
// DELETE PLAN
// =====================

Route::delete('/plans/{id}', function ($id, Request $request) {
    DB::table('plans')
        ->where('id', $id)
        ->where('user_id', $request->user()->id)
        ->delete();

    return ['message' => 'Plan deleted'];
})->middleware('auth:sanctum');