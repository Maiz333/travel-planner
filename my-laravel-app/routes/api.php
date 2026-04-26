<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/test', function () {
    return response()->json([
        'message' => 'Backend works!'
    ]);
});

Route::get('/cities', function () {
    return DB::table('cities')->get();
});

Route::get('/seed-cities', function () {
    DB::table('cities')->insert([
        ['name' => 'Paris'],
        ['name' => 'London'],
        ['name' => 'Tokyo'],
        ['name' => 'Rome']
    ]);

    return 'Cities added';
});

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
    ]);

    return 'Places reset and added';
});

Route::post('/plans', function (Request $request) {
    DB::table('plans')->insert([
        'title' => $request->title,
        'places' => json_encode($request->places),
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return ['message' => 'Trip plan saved'];
});