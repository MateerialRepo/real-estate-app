<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Tenant\AuthController;
use App\Http\Controllers\Api\Tenant\TenantController;
use App\Http\Controllers\Api\Ticket\TicketController;
use App\Http\Controllers\Api\Tenant\RefereeController;
use App\Http\Controllers\Api\Admin\AdminAuthController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\Tenant\NextOfKinController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\Landlord\LandlordController;
use App\Http\Controllers\Api\Property\PropertyController;
use App\Http\Controllers\Api\Landlord\LandlordAuthController;
use App\Http\Controllers\Api\Property\PropertyLikeController;
use App\Http\Controllers\Api\Property\PropertyReservationController;
use App\Http\Controllers\Api\Property\PropertyVerificationController;

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

Route::get('/test', function () {
    return 'API is up+';
});



Route::group(['middleware' => ['cors', 'json.response']], function () {

    // Tenant routes
    Route::prefix('v1/tenant')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:tenant')->group(function () {
            Route::get('/', [TenantController::class, 'show']);
            Route::post('/kyc-update', [TenantController::class, 'updateTenantKYC']);
            Route::post('/password-update', [TenantController::class, 'updatePassword']);
            Route::post('/update-profile', [TenantController::class, 'update']);
            Route::post('/upload-pic', [TenantController::class, 'uploadProfilePic']);
            Route::post('/add-update/referee', RefereeController::class);
            Route::post('/add-update/next-of-kin', NextOfKinController::class);

            // ticket routes
            Route::get('/ticket', [TicketController::class, 'fetchAll']);
            Route::get('/ticket/{unique_id}', [TicketController::class, 'fetchSingle']);
            Route::post('/ticket', [TicketController::class, 'createTicket']);
            Route::post('/ticket/{unique_id}/resolve', [TicketController::class, 'resolveTicket']);
            Route::post('/ticket/{unique_id}/reopen', [TicketController::class, 'reopenTicket']);
            Route::delete('/ticket/{unique_id}', [TicketController::class, 'deleteTicket']);

            // Document routes
            Route::get('/document', [DocumentController::class, 'fetchAllTenantDocument']);
            Route::get('/document/{unique_id}', [DocumentController::class, 'fetchSingleDocument']);
            Route::post('/document', [DocumentController::class, 'createDocument']);
            Route::post('/document/{unique_id}', [DocumentController::class, 'updateDocument']);
            Route::delete('/document/{unique_id}', [DocumentController::class, 'deleteDocument']);

            // property routes
            Route::get('/properties', [PropertyController::class, 'fetchVerifiedProperties']); //test this
            Route::post('/property/like/{propertyId}', [PropertyLikeController::class, 'likeProperty']);
            Route::post('/property/unlike/{propertyId}', [PropertyLikeController::class, 'unlikeProperty']);
            Route::post('/property/reserve/{propertyId}', [PropertyReservationController::class, 'reserveProperty']);

            // payment routes
            Route::get('/payment/callback', [PaymentController::class, 'handleGatewayCallback']);



            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });


    // LAndlord Routes
    Route::prefix('v1/landlord')->group(function () {
        Route::post('/register', [LandlordAuthController::class, 'register']);
        Route::post('/login', [LandlordAuthController::class, 'login']);

        Route::middleware('auth:landlord')->group(function () {
            Route::get('/', [LandlordController::class, 'index']);
            Route::post('/kyc-update', [LandlordController::class, 'updateLandlordKYC']); //test
            Route::post('/password-update', [LandlordController::class, 'updatePassword']); //create and test
            Route::post('/update-profile', [LandlordController::class, 'updateLandlord']); //test
            Route::post('/upload-pic', [LandlordController::class, 'uploadProfilePic']); //create and test
            Route::get('/property', [PropertyController::class, 'index']); //create and test
            Route::get('/property/{unique_id}', [PropertyController::class, 'getProperty']); //create and test
            Route::post('/save-property', [PropertyController::class, 'createProperty']); //done
            Route::post('/update-property/{unique_id}', [PropertyController::class, 'updateProperty']); //create and test
            Route::post('/verify-property/{id}', [PropertyVerificationController::class, 'verifyProperty']); //create and test
            Route::delete('/property/{unique_id}', [PropertyController::class, 'deleteProperty']); //create and test

            // Document routes
            Route::get('/document', [DocumentController::class, 'fetchAllLAndlordDocument']);
            Route::get('/document/{unique_id}', [DocumentController::class, 'fetchSingleDocument']);
            Route::post('/document', [DocumentController::class, 'createLandlordDocument']);
            Route::post('/document/{unique_id}', [DocumentController::class, 'updateDocument']);
            Route::delete('/document/{unique_id}', [DocumentController::class, 'deleteDocument']);

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

            // Property activities                                              
            Route::get('/property', [AdminController::class, 'allProperties']);
            Route::get('/property/{id}', [AdminController::class, 'singleProperty']);
            Route::delete('/property/{id}', [AdminController::class, 'destroyProperty']);
            Route::post('/property/{id}/verify', [PropertyVerificationController::class, 'adminVerifyProperty']);


            Route::post('/logout', [AdminAuthController::class, 'logout']);
        });
    });
});
