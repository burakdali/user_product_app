<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WEB\DashboardController;
use App\Http\Controllers\WEB\WebProductsController;
use App\Http\Controllers\WEB\WebUsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    #users:
    Route::get('/users', [WebUsersController::class, 'index'])->name('admin.users');
    Route::post('/editUser/{id}', [WebUsersController::class, 'editUser'])->name('admin.editUser');
    Route::post('/updateUser', [WebUsersController::class, 'updateUser'])->name('admin.updateUser');
    Route::get('/getUsers', [WebUsersController::class, 'getUsers'])->name('admin.getUsers');
    Route::post('/assignSave', [WebUsersController::class, 'assignSave'])->name('assignSave');
    Route::get('/getProductsToAssign', [WebUsersController::class, 'getProductsToAssign'])->name('admin.getProductsToAssign');
    Route::delete('/deleteUser/{id}', [WebUsersController::class, 'destroy'])->name('admin.deleteUser');
    Route::get('/getUserProducts/{id}', [WebUsersController::class, 'getUserProducts'])->name('admin.getUserProducts');

    #products:
    Route::get('/products', [WebProductsController::class, 'index'])->name('admin.products');
    Route::get('/getProducts', [WebProductsController::class, 'getProducts'])->name('admin.getProducts');
    Route::delete('/deleteProduct/{id}', [WebProductsController::class, 'deleteProduct'])->name('admin.deleteProduct');
    Route::post('/editProduct/{id}/', [WebProductsController::class, 'editProduct'])->name('admin.editProduct');
    Route::post('/updateProduct', [WebProductsController::class, 'updateProduct'])->name('admin.updateProduct');
});
Route::get('/userProducts', [WebUsersController::class, 'userProducts'])->name('user.userProducts')->middleware('auth');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
