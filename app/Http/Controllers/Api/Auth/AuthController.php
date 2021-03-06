<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\TenantRequest;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $tenant = Tenant::where('email', request()->email)->first();

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'error_type' => 'tenant_not_exist',
                'message' => 'Tenant does not exist'
            ], 404);
        }

        if (!Hash::check($request->password, $tenant->password)) {
            return response()->json([
                'success' => false,
                'error_type' => 'password_mismatch',
                'message' => 'Password mismatch'
            ], 401);
        }

        Auth::login($tenant);

        $token = $tenant->createToken('Breics Tenant');

        return response()->json([
            'success' => true,
            'token' => $token->accessToken,
            'user' => $tenant
        ], 200);
    }


    public function register(TenantRequest $request) //TenantRequest 
    {

        try {
            $tenant = Tenant::firstOrCreate($request->validated());

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

    public function logout(Request $request){
        $landlord = Auth::guard('tenant')->user()->token();
        $landlord->revoke();
        $data['status'] = 'Success';
        $data['message']= 'Successfully logged out';
        return response()->json($data, 200);
    }
}
