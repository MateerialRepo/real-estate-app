<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\NextOfKinRequest;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class NextOfKinController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(NextOfKinRequest $request, Tenant $tenant)
    {
        $tenant = Auth::user();

        try {
            $tenant->nextOfKin()->updateOrCreate(
                ['tenant_id' => $tenant->id],
                $request->validated()
            );

            $tenant = Auth::user()->load('nextOfKin');

            return response()->json([
                'success' => true,
                'user' => $tenant
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => true,
                'error_type' => 'server_error',
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
