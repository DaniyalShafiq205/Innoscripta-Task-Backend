<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserPreferenceController;

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




Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::middleware('jwt.auth')->group(function () {

Route::get('/me', [UserController::class, 'me'])->name('me');
Route::post('/refresh', [UserController::class, 'refresh']);
Route::post('/logout', [UserController::class, 'logout']);



Route::get('/preferences', [UserPreferenceController::class, 'index']);
Route::post('/preferences/addToUser', [UserPreferenceController::class, 'addToUser']);


Route::get('/articles', ArticleController::class);
});
