<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Landlord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LandlordAuthRequest;
use App\Http\Requests\LandlordLoginRequest;

class LandlordAuthController extends Controller
{
    public function login(Request $request)
    {
        try{

            $landlord = Landlord::where('email', request()->email)->first();

            if (!$landlord) {
                return response()->json([
                    'success' => false,
                    'error_type' => 'Landlord_not_exist',
                    'message' => 'Landlord does not exist'
                ], 404);
            }

            if (!Hash::check($request->password, $landlord->password)) {
                return response()->json([
                    'success' => false,
                    'error_type' => 'password_mismatch',
                    'message' => 'Password mismatch'
                ], 401);
            }

            Auth::login($landlord);

            $token = $landlord->createToken('Breics Landlord');

            return response()->json([
                'success' => true,
                'token' => $token->accessToken,
                'user' => $landlord
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'error_type' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function register(LandlordAuthRequest $request)
    {
        try {
            $landlord = landlord::firstOrCreate($request->validated());
            return response()->json([
                'success' => true,
                'user' => $landlord
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => true,
                'error_type' => 'server_error',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request){
        $landlord = Auth::guard('landlord')->user()->token();
        $landlord->revoke();
        $data['status'] = 'Success';
        $data['message']= 'Successfully logged out';
        return response()->json($data, 200);
    }
}
