<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;

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

Route::middleware('web')->group(function () {
    Route::post('log-in', [UserController::class, 'log_in'])->name('log-in');
    Route::post('RegisterWithPhoneNumber', [UserController::class, 'RegisterWithPhoneNumber'])->name('RegisterWithPhoneNumber');
    Route::post('RegisterWithEmail', [UserController::class, 'RegisterWithEmail'])->name('RegisterWithEmail');
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('/products', ProductController::class);
    Route::get('/getProduct/{value}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/deleteProduct/{id}', [ProductController::class, 'destroy']);
    Route::post('/assignProduct', [ProductController::class, 'assign']);


    Route::apiResource('/users', UserController::class);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::get('/getUser/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/deleteUser/{id}', [UserController::class, 'destroy']);
});

Route::get('/getAuthenticatedUserProducts', [UserController::class, 'getAuthenticatedUserProducts'])->middleware('auth:sanctum');

Route::post('/getUserProducts/{id}', [UserController::class, 'getUserProducts']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
