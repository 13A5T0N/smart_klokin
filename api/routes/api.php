<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controller\employee;
use App\Http\Controllers\login;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [App\Http\Controllers\LoginController::class, 'login']);

Route::post('time_out', [App\Http\Controllers\LoginController::class, 'time_out']);

Route::post('break_in', [App\Http\Controllers\LoginController::class, 'break_in']);

Route::post('break_out', [App\Http\Controllers\LoginController::class, 'break_out']);

Route::post('switch_in', [App\Http\Controllers\LoginController::class, 'switch_in']);

Route::post('switch_out', [App\Http\Controllers\LoginController::class, 'switch_out']);