<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Models\Tenant;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Nullable;

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
        $tenant = Auth::user();

        $property = Property::where('tenant_id', $tenant->id)->first();

        if($property){
            $transaction = Transaction::where('property_id', $property->id)->where('tenant_id', $tenant->id)->first();
            $rent_date = Carbon::parse($transaction->created_at);
            $rent_expiry = $rent_date->addMonths($transaction->duration);
            $rent_expiry = $rent_expiry->format('d-m-Y');
            $tenant['rent_expiry'] = $rent_expiry;
            $tenant['property'] = $property;
            return response()->json($tenant, 200);
        } else{
            return Auth::user();
        }

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
            
            $tenant = Auth::user();
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

            $tenant = Auth::user();

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


    public function updatePassword(Request $request){
        try{

            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        
            $tenant = Auth::user();

            if (!Hash::check($request->current_password, $tenant->password)) {
                return response()->json(['error'=>'Current password does not match!'], 401);
            }


            $tenant->password = bcrypt($request->new_password);
            $tenant->save();


            $data['status'] = 'Success';
            $data['message'] = 'Password Updated Successfully';
            return response()->json($data, 200);

        }catch(\Exception $exception){

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }


    public function updateTenantKYC(Request $request){

        try{

            $validator = Validator::make($request->all(), [
                'kyc_img' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
            
            $idVerification = time().'.'.$request->kyc_img->extension();

            $tenant = Auth::user();
            $tenant->update([
                'is_approved' => true,
                'kyc_type' => "NIN",
                'kyc_id' => env('APP_URL')."/tenants/tenantkyc/".$idVerification
                ]);
            
            $request->kyc_img->move(public_path('/tenants/tenantkyc'), $idVerification);

            $tenant = Auth::user();            
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

    public function uploadProfilePic(Request $request){

        try{

            $validator = Validator::make($request->all(), [
                'profile_pic' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
            
            $profilepic = time().'.'.$request->profile_pic->extension();

            $tenant = Auth::user();
            $tenant->update([
                'profile_pic' => env('APP_URL')."/tenants/tenantprofilepic/".$profilepic
                ]);

            $request->profile_pic->move(public_path('/tenants/tenantprofilepic'), $profilepic);

            $tenant = Auth::user();            
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

    public function activeRentalCard()
    {
        $tenant = Auth::user();
        $property = Property::where('tenant_id', $tenant->id)->first();

        if(!$property){
            $data['status'] = 'Failed';
            $data['message'] = 'No Active Rental';
            return response()->json($data, 400);
        }

        $landlord = Landlord::where('id', $property->landlord_id)->first();
        $landlord->property = "";
        $transaction = Transaction::where('property_id', $property->id)->where('tenant_id', $tenant->id)->first();
        $rent_date = Carbon::parse($transaction->created_at);
        $rent_expiry = $rent_date->addMonths($transaction->duration);
        $rent_expiry = $rent_expiry->format('d-m-Y');

        $data['status'] = 'Success';
        $data['message'] = 'Rental Card';
        $data['property'] = $property;
        $data['landlord'] = $landlord;
        $data['rent_expiry'] = $rent_expiry;
        return response()->json($data, 200);
    }


    public function terminateRent(){
            
            try{
                $tenant = Auth::user();
                $property = Property::where('tenant_id', $tenant->id)->first();
                $property->update([
                    'tenant_id' => null,
                    ]);
    
                $data['status'] = 'Success';
                $data['message'] = 'Rent Terminated Successfully';
                return response()->json($data, 200);
    
            } catch (\Exception $exception) {
    
                $data['status'] = 'Failed';
                $data['message'] = $exception->getMessage();
                return response()->json($data, 400);
            }
    
    }


     //*********************************Handling ADmin tenants activities*************************************************/ 
     public function allTenants()
     {
        //  dd('We got here o');

         try{
 
             $data['status'] = 'Success';
             $data['message'] = 'Tenants retrieved successfully';
             $data['data'] = Tenant::orderBy('created_at', 'desc')->get();
            // $data['data'] = DB::table('tenants')->get();
 
         } catch (\Exception $e) {
 
             $data['status'] = 'Failed';
             $data['error'] = $e->getMessage();
             return response()->json($data, 500);
 
         }

         return response()->json($data, 200);

         
     }
 
     public function singleTenant(Tenant $tenant, $id)
     {
         try{
 
             $data['status'] = 'Success';
             $data['message'] = 'Tenant retrieved successfully';
             $data['data'] = $tenant->find($id);
             return response()->json($data, 200);
 
         } catch (\Exception $e) {
 
             $data['status'] = 'Failed';
             $data['error'] = $e->getMessage();
             return response()->json($data, 500);
 
         }
         
     }
 
     public function destroyTenant(Tenant $tenant, $id)
     {
 
         $tenant->delete();
 
         return response()->json(['message' => 'Tenant deleted successfully']);
     }
}
