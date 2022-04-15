<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index']);
Route::post('/new-json', [\App\Http\Controllers\JsonController::class, 'newJson']);
Route::get('/show-json/{json}', [\App\Http\Controllers\JsonController::class, 'showJson']);
Route::post('/examples', [\App\Http\Controllers\JsonController::class, 'examples']);
