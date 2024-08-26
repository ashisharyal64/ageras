<?php

use App\Http\Controllers\Api\V1\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', [GameController::class, 'index']);
Route::post('/restart', [GameController::class, 'restart']);
Route::post('/{piece}', [GameController::class, 'piece']);
Route::delete('/', [GameController::class, 'destroy']);
