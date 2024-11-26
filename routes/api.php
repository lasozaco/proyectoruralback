<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\MultimediaController;
use App\Http\Controllers\Api\RolController;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::resource('roles', RolController::class)->names('roles');
    Route::resource('institution', InstitutionController::class)->names('institution');

    Route::resource('event', EventController::class)->names('event');

    Route::resource('multimedia', MultimediaController::class)->names('multimedia');
});

Route::get('institutions', [InstitutionController::class, 'indexPublic']);

Route::get('events/{id}', [EventController::class, 'show']);

Route::get('multimedias/{id}', [MultimediaController::class, 'indexPublic']);
