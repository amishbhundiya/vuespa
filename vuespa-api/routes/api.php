<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Products\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/signup', [AuthController::class, 'doRegister']);
Route::post('/login', [AuthController::class, 'doLogin']);
Route::post('/check-forgot-password-token', [AuthController::class, 'checkForgotPasswordToken']);
Route::post('/forgot-password', [AuthController::class, 'doForgotPassword']);
Route::post('/reset-password', [AuthController::class, 'doResetPassword']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/userauth', [AuthController::class, 'checkUserAuth']);
    Route::post('/logout', [AuthController::class, 'doLogout']);

    Route::post('/products', [ProductController::class, 'index']);
    Route::post('/products/store', [ProductController::class, 'store']);
    Route::post('/products/{id}/edit', [ProductController::class, 'edit']);
    Route::post('/products/{id}/update', [ProductController::class, 'update']);
    Route::post('/products/{id}/destroy', [ProductController::class, 'destroy']);
    Route::post('/products/import', [ProductController::class, 'import']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});