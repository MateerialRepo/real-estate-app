<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Tenant\AuthController;
use App\Http\Controllers\Api\Tenant\TenantController;
use App\Http\Controllers\Api\Ticket\TicketController;
use App\Http\Controllers\Api\Tenant\RefereeController;
use App\Http\Controllers\Api\Tenant\NextOfKinController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\Landlord\LandlordController;
use App\Http\Controllers\Api\Landlord\LandlordAuthController;
use App\Http\Controllers\Api\Landlord\PropertyController;

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

Route::get('/test', function(){
    return 'API is up+';
});

Route::get('v1/tenant/register', [AuthController::class, 'register']);
Route::get('v1/tenant/login', [AuthController::class, 'login']);

// Landlord test routes
// Route::get('v1/landlord/register', [LandlordAuthController::class, 'register']);
// Route::get('v1/landlord/login', [LandlordAuthController::class, 'login']);



Route::group(['middleware' => ['cors', 'json.response']], function () {

    // LAndlord Routes
    Route::prefix('v1/landlord')->group(function () {
        Route::post('/register', [LandlordAuthController::class, 'register']);
        Route::post('/login', [LandlordAuthController::class, 'login']);

        Route::middleware('auth:landlord')->group(function () {
            Route::get('/', [LandlordController::class, 'index']);
            Route::post('/kyc-update', [LandlordController::class, 'updateLandlordKYC']);
            Route::post('/update-profile', [LandlordController::class, 'updateLandlord']);
            Route::post('/save-property', [PropertyController::class, 'createProperty']);
            Route::get('/property', [PropertyController::class, 'index']);
            Route::get('/property/{id}', [PropertyController::class, 'getProperty']);

            Route::post('/logout', [LandlordAuthController::class, 'logout']);
        });

    });


    // Admin Routes
    Route::prefix('v1/admin')->group(function () {
        Route::post('/login', [AdminAuthController::class, 'login']);

        Route::middleware('auth:admin')->group(function () {
            Route::get('/', [AdminController::class, 'index']);

            // Tenant activities
            Route::get('/tenant', [AdminController::class, 'allTenants']);
            Route::get('/tenant/{id}', [AdminController::class, 'singleTenant']);
            Route::delete('/tenant/{id}', [AdminController::class, 'destroyTenant']);

            // Landlord activities
            Route::get('/landlord', [AdminController::class, 'allLandlords']);
            Route::get('/landlord/{id}', [AdminController::class, 'singleLandlord']);
            Route::delete('/landlord/{id}', [AdminController::class, 'destroyLandlord']);
            

            Route::post('/logout', [AdminAuthController::class, 'logout']);
        });

    });

});
    
