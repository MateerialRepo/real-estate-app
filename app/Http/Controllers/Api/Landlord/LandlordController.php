<?php

namespace App\Http\Controllers\Api\Landlord;

use App\Models\Tenant;
use App\Models\Ticket;
use App\Models\Property;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\SupportTicketRequest;
use App\Http\Requests\UpdateLandlordProfileRequest;

class LandlordController extends Controller
{

    public function index(){
        $landlord = Auth::user();
        return response()->json($landlord);
    }


    public function updatePassword(Request $request){
        try{

            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }


            $landlord = Auth::guard('landlord')->user();

            if (!Hash::check($request->current_password, $landlord->password)) {
                return response()->json(['error'=>'Current password does not match!'], 401);
            }

            $landlord->update(
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

    public function uploadProfilePic(Request $request){

        try{

            $validator = Validator::make($request->all(), [
                'profile_pic' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
            
            $profilepic = time().'.'.$request->profile_pic->extension();

            $landlord = Auth::guard('landlord')->user();
            $landlord->update([
                'profile_pic' => env('APP_URL')."/landlords/landlordprofilepic/".$profilepic
                ]);

            $request->profile_pic->move(public_path('/landlords/landlordprofilepic'), $profilepic);

            $landlord = Auth::guard('landlord')->user();
            $data['status'] = 'Success';
            $data['message'] = 'Profile Pic Uploaded Successfully';
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
            $landlord->user()->update([
                'kyc_type' => $request->kyc_type,
                'kyc_id' => env('APP_URL')."/landlords/landlordkyc/".$idVerification,
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


    // Overview for landlord
    public function overview()
    {
        $landlord = Auth::user();
        $noOfTotalListedProperties = Property::where('landlord_id', $landlord->id)->count();
        // Total number of properties available
        $noOfAvailableProperties = Property::where('landlord_id', $landlord->id)->where('is_available', 'available')->count();
        // Total number of properties not available
        $noOfRentedProperties = $noOfTotalListedProperties - $noOfAvailableProperties;
        // Total number of properties not verified
        $noOfVerifiedProperties = Property::where('landlord_id', $landlord->id)->where('is_verified', 'verified')->count();
        $noOfUnverifiedProperties = $noOfTotalListedProperties - $noOfVerifiedProperties;

        // total number of tickets assigned to landlord
        $noOfTickets = Ticket::where('landlord_id', $landlord->id)->count();

        // total number of Tenants through properties using eloquent relationship


        $data['status'] = 'Success';
        $data['TotalListedProperties'] = $noOfTotalListedProperties;
        $data['AvailableProperties'] = $noOfAvailableProperties;
        $data['RentedProperties'] = $noOfRentedProperties;
        $data['VerifiedProperties'] = $noOfVerifiedProperties;
        $data['UnverifiedProperties'] = $noOfUnverifiedProperties;
        $data['AssignedTickets'] = $noOfTickets;

        return response()->json($data, 200);
    }

    // get all tenants through properties
    public function getAllTenants()
    {
        $landlord = Auth::user();
        $properties = Property::where('landlord_id', $landlord->id)->get();
        // $tenants = [];
        $i = 0;
        foreach ($properties as $property) {
            $tenant[$i] = Tenant::where('id', $property->tenant_id)->get();
            $i++;
        }
        $data['status'] = 'Success';
        $data['data'] = $tenant;
        return response()->json($data, 200);
    }

    // // get single tenant details
    // public function getTenantDetails($id)
    // {
    //     $tenant = Tenant::where('id', $id)->first();
    //     $data['status'] = 'Success';
    //     $data['data'] = $tenant;
    //     return response()->json($data, 200);
    // }

}
