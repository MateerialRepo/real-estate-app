<?php

use App\Http\Controllers\Api\Tenant\AuthController;
use App\Http\Controllers\Api\Tenant\NextOfKinController;
use App\Http\Controllers\Api\Tenant\RefereeController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::prefix('tenant')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);

        Route::middleware('auth:tenant')->group(function () {
            Route::post('/add-update/referee', RefereeController::class);
            Route::post('/add-update/next-of-kin', NextOfKinController::class);
        });
    });
});
