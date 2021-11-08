<?php

namespace App\Http\Controllers\Api\Landlord;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateLandlordProfileRequest;

class LandlordController extends Controller
{

    public function index(){
        $landlord = Auth::user();
        return response()->json($landlord);
    }

    public function updateLandlord(UpdateLandlordProfileRequest $request)
    {
        //
        try{
            
            $landlord = Auth::guard('landlord')->user();
            $landlord->update($request->validated());

            $landlord = Auth::guard('landlord')->user();

            $data['status'] = 'Success';
            $data['message'] = 'Landlord Profile Update Successful';
            $data['data'] = $landlord;
            return response()->json($data, 200);

       } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);

       }    
    }


    public function updateLandlordKYC(Request $request){

        try{

            $validator = Validator::make($request->all(), [
                'kyc_id' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
                'kyc_type' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
            
            $idVerification = time().'.'.$request->kyc_id->extension();

            $landlord = Auth::guard('landlord');
            // dd($landlord->user());
            $landlord->user()->update([
                'kyc_type' => $request->kyc_type,
                'kyc_id' => "/landlords/landlordkyc/".$idVerification,
                'is_approved' => true
                ]);
            
            $request->kyc_id->move(public_path('/landlords/landlordkyc'), $idVerification);

            $landlord = Auth::guard('landlord');            
            $data['status'] = 'Success';
            $data['message'] = 'KYC Image Successfully Uploaded';
            $data['data'] = $landlord->user();
            return response()->json($data, 200);

        } catch (\Exception $exception) {
            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }

    }

    
}
