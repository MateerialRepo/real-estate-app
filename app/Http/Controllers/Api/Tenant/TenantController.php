<?php

namespace App\Http\Controllers\Api\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return Auth::tenant()->load('tenantNextOfKin', 'tenantReferee');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try{
            
            $tenant = Auth::tenant();
            $tenant->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'dob' => $request->dob,
                'occupation' => $request->occupation,
                'gender' => $request->gender,
                'address' => $request->address,
                'landmark' => $request->landmark,
                'state'=> $request->state,
                'country' => $request->country
                ]);

            $tenant = Auth::tenant()->load('tenantNextOfKin', 'tenantReferee');

            $data['status'] = 'Success';
            $data['message'] = 'tenant Profile Update Successful';
            $data['data'] = $tenant;
            return response()->json($data, 200);

       } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);

       }
    }


    public function updatepassword(Request $request){
        try{

            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }


            $tenant = Auth::tenant();

            if (!Hash::check($request->current_password, $tenant->password)) {
                return response()->json(['error'=>'Current password does not match!'], 401);
            }

            $tenant->update(
                ['password' => Hash::make($request->new_password)]
            );


            $data['status'] = 'Success';
            $data['message'] = 'Password Updated Successfully';
            return response()->json($data, 200);

        }catch(\Exception $exception){
            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }


    public function updatetenantKYC(Request $request){

        try{

            $validator = Validator::make($request->all(), [
                'kyc_img' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
            
            $idVerification = time().'.'.$request->kyc_img->extension();

            $tenant = Auth::tenant();
            $tenant->update([
                'KYC_status' => "completed",
                'KYC_type' => "NIN",
                'KYC_id' => "/tenants/tenantkyc/".$idVerification
                ]);
            
            $request->kyc_img->move(public_path('/tenants/tenantkyc'), $idVerification);

            $tenant = Auth::tenant()->load('tenantNextOfKin', 'tenantReferee');            
            $data['status'] = 'Success';
            $data['message'] = 'KYC Image Successfully Uploaded';
            $data['data'] = $tenant;
            return response()->json($data, 200);

        } catch (\Exception $exception) {
            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }

    }

    public function uploadprofilepic(Request $request){

        try{

            $validator = Validator::make($request->all(), [
                'profile_pic' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
            
            $profilepic = time().'.'.$request->profile_pic->extension();

            $tenant = Auth::tenant();
            $tenant->update([
                'profile_pic' => "/tenants/tenantprofilepic/".$profilepic
                ]);

            $request->profile_pic->move(public_path('/tenants/tenantprofilepic'), $profilepic);

            $tenant = Auth::tenant()->load('tenantNextOfKin', 'tenantReferee');            
            $data['status'] = 'Success';
            $data['message'] = 'Profile Pic Uploaded Successfully';
            $data['data'] = $tenant;
            
            return response()->json($data, 200);

        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }

    }

    public function destroy($id)
    {
        //
    }
}
