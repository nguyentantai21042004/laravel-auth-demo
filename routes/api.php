<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('users')->middleware('jwt')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\UserController::class, 'show']);
    Route::post('/', [\App\Http\Controllers\UserController::class, 'store']);
    Route::put('/{id}', [\App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\UserController::class, 'destroy']);
});
