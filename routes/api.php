<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'API'], function(){
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    Route::group(['middleware' => ['verify.token', 'lang', 'checkpass']], function(){
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('refresh', [AuthController::class, "refresh"])->name("refresh");
        Route::post('create', [CategoryController::class, 'create'])->name('create');
        Route::get('getById', [CategoryController::class, 'getById'])->name('getById');
        Route::post('update', [CategoryController::class, 'update'])->name('update');
        Route::delete('delete', [CategoryController::class, 'delete'])->name('delete');
        Route::get('getAll', [CategoryController::class, 'getAll'])->name('getAll');
    });
    
});
