<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\RefereeRequest;
use Illuminate\Support\Facades\Auth;

class RefereeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RefereeRequest $request)
    {
        $tenant = Auth::user();

        try {
            $tenant->referee()->updateOrCreate(
                ['tenant_id' => $tenant->id],
                $request->validated()
            );

            $tenant = Auth::user()->load('referee');

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
