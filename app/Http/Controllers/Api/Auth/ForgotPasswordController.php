<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Tenant;
use App\Models\Landlord;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $email = $request->input('email');

        // check if $email exists in Tenant or landlord models
        $tenant_DoesntExists = Tenant::where('email', $email)->doesntExist();
        $landlord_DoesntExists = Landlord::where('email', $email)->doesntExist();

        if ($tenant_DoesntExists && $landlord_DoesntExists) {
            return response()->json(['message' => 'Email not found'], 404);
        } else {

            $token = Str::random(20);

            try {

                DB::table('password_resets')->where('email', $email)->delete();

                DB::table('password_resets')->insert([
                    'email' => $email,
                    'token' => $token,
                ]);

                //send email
                Mail::send('Mails.forgot', ['token' => $token], function (Message $message) use($email) {
                    $message->to($email);
                    $message->subject('Password Reset');
                });

                return response()->json([
                    'message' => 'Email Successfully Sent, Please check your email'
                ], 200);

            } catch (\Exception $exception) {

                return response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage()
                ], 400);
            }
        }

        
        
    }


    public function reset(Request $request){

        $validator = Validator::make($request->all(), [
            'token' => 'required|exists:password_resets,token',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required|string|same:password'
        ]);

        $token = $request->input('token');

        if(!$passwordResets = DB::table('password_resets')->where('token', $token)->first()){
            return response()->json([
                'message' => 'Invalid Token'
            ], 404);
        }

        if($tenant = Tenant::where('email', $passwordResets->email)->first())
        {

            $tenant->password = bcrypt($request->input('password'));
            $tenant->save(); 

        }elseif($landlord = Landlord::where('email', $passwordResets->email)->first()){

            $landlord->password = bcrypt($request->input('password'));
            $landlord->save(); 

        }else{

            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        DB::table('password_resets')->where('token', $token)->delete();

        return response()->json([
            'message' => 'Password Reset Successfully'
        ], 200);
    }

    
}
