<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        try{

            $admin = Admin::where('email', request()->email)->first();

            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'error_type' => 'admin_not_exist',
                    'message' => 'admin does not exist'
                ], 404);
            }

            if (!Hash::check($request->password, $admin->password)) {
                return response()->json([
                    'success' => false,
                    'error_type' => 'password_mismatch',
                    'message' => 'Password mismatch'
                ], 401);
            }

            Auth::login($admin);

            $token = $admin->createToken('Breics admin');

            return response()->json([
                'success' => true,
                'token' => $token->accessToken,
                'user' => $admin
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'error_type' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function logout(Request $request){
        $admin = Auth::guard('admin')->user()->token();
        $admin->revoke();
        $data['status'] = 'Success';
        $data['message']= 'Successfully logged out';
        return response()->json($data, 200);
    }
}
