<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\RolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::resource('roles', RolController::class)->names('roles');
    Route::resource('institution', InstitutionController::class)->names('institution');
});

Route::get('institutions', [InstitutionController::class, 'indexPublic']);