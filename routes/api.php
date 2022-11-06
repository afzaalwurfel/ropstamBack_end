<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CarsController;

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('cars', [CarsController::class, 'index']);
    Route::get('cars/{id}', [CarsController::class, 'show']);
    Route::post('create', [CarsController::class, 'store']);
    Route::put('update/{car}',  [CarsController::class, 'update']);
    Route::delete('delete/{car}',  [CarsController::class, 'destroy']);
});