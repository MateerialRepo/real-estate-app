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

});
    
//     Route::get('v1/admin/tenant', [AdminController::class, 'allTenants']);

//     // Admin routes
//     Route::prefix('v1/admin')->group(function () {
    

//     });

//     // Tenant routes
//     Route::prefix('v1/tenant')->group(function () {
//         Route::post('/login', [AuthController::class, 'login']);
//         Route::post('/register', [AuthController::class, 'register']);
        
//         //tickets or facilities
//         Route::get('/ticket', [TicketController::class, 'fetchAll'] );
//         Route::get('/ticket/{unique_id}', [TicketController::class, 'fetchSingle'] );
//         Route::post('/ticket/create', [TicketController::class, 'createAndUpdate'] ); //not working
//         Route::post('/ticket/comment/{unique_id}', [TicketController::class, 'ticketComment'] );
//         Route::get('/ticket/resolve/{unique_id}', [TicketController::class, 'resolveTicket'] );
//         Route::get('/ticket/reopen/{unique_id}', [TicketController::class, 'reopenTicket'] );
//         Route::delete('/ticket/{unique_id}', [TicketController::class, 'deleteTicket'] );

//         // Documents
//         Route::get('/document', [DocumentController::class, 'fetchAllDocument'] );
//         Route::get('/document/{unique_id}', [DocumentController::class, 'fetchSingleDocument'] );
//         Route::post('/document/create', [DocumentController::class, 'createAndUpdate'] ); //not working
//         Route::delete('/document/{unique_id}', [DocumentController::class, 'deleteDocument'] );

//         // Tenant Routes
//         Route::middleware('auth:tenant')->group(function () {
//             Route::post('/add-update/referee', RefereeController::class);
//             Route::post('/add-update/next-of-kin', NextOfKinController::class);
//             Route::post('/tenant', [TenantController::class, 'updateProfile'] );
//             Route::post('/tenant-kyc-update', [TenantController::class, 'updateUserKYC'] );
//             Route::post('/tenant-profile-pic', [TenantController::class, 'uploadprofilepic'] );
//             Route::post('/tenant-password-update', [TenantController::class, 'updatepassword'] );
//             Route::post('/logout', [AuthController::class, 'logout'] );
//         });

        
//     });


//     // Landlord routes
//     Route::prefix('landlord')->group(function () {
//         Route::post('/login', [AuthController::class, 'login']);
//         Route::post('/register', [AuthController::class, 'register']);

//         //tickets or facilities
//         Route::get('/ticket', [TicketController::class, 'fetchAll'] );
//         Route::get('/ticket/{unique_id}', [TicketController::class, 'fetchSingle'] );
//         Route::post('/ticket/create', [TicketController::class, 'createAndUpdate'] ); //not working
//         Route::post('/ticket/comment/{unique_id}', [TicketController::class, 'ticketComment'] );
//         Route::get('/ticket/resolve/{unique_id}', [TicketController::class, 'resolveTicket'] );
//         Route::get('/ticket/reopen/{unique_id}', [TicketController::class, 'reopenTicket'] );
//         Route::delete('/ticket/{unique_id}', [TicketController::class, 'deleteTicket'] );

//         // Documents
//         Route::get('/document', [DocumentController::class, 'fetchAllDocument'] );
//         Route::get('/document/{unique_id}', [DocumentController::class, 'fetchSingleDocument'] );
//         Route::post('/document/create', [DocumentController::class, 'createAndUpdate'] ); //not working
//         Route::delete('/document/{unique_id}', [DocumentController::class, 'deleteDocument'] );

//         // Landlord Routes
//         Route::middleware('auth:landlord')->group(function () {
//             Route::post('/landlord', [LandlordController::class, 'updateProfile'] );
//             Route::post('/landlord-kyc-update', [LandlordController::class, 'updateUserKYC'] );
//             Route::post('/landlord-profile-pic', [LandlordController::class, 'uploadprofilepic'] );
//             Route::post('/landlord-password-update', [LandlordController::class, 'updatepassword'] );
//             Route::post('/logout', [AuthController::class, 'logout'] );
//         });
//     });
// });
