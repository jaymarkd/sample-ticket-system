<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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
Route::post('login',[AuthController::class, 'apiLogin']);

Route::middleware(['auth:api'])->group( function() {
    Route::get('my', [UserController::class,'profile'])->name('myprofile');
});

Route::prefix('users')->middleware(['auth:api'])->group(function () {

    Route::get('/', [UserController::class, 'apiGetAll'])->name('users.getAll');

    Route::get('/{id}', [UserController::class, 'apiGetOne'])->name('users.getOne');

    Route::post('/', [UserController::class, 'apiCreateUser'])->name('users.createUser');

    // Route::put('/{id}', [UserController::class, 'update'])->name('users.updateUser');

    // Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroyUser');

});
