<?php

namespace App\Http\Controllers\Api\Property;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\PropertyReservation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PropertyReservationController extends Controller
{
    //reserve a property
    public function reserveProperty($property_id)
    {
        $isPropertyReserved = PropertyReservation::where('property_id',$property_id)
        ->where('isReserved',true)
        ->first();

        if($isPropertyReserved){
            return response()->json(['message' => 'Property is already reserved'], 400);
        }

        PropertyReservation::create([
            'tenant_id' => Auth::user()->id,
            'property_id' => $property_id,
            'isReserved' => true,
        ]);
        
        $data['status'] = 'Success';
        $data['message'] = 'Property Reserved Successfully';
        return response()->json($data, 200);
    }


    // fetch reserved properties
    public function fetchReservedProperties()
    {
        $reservedProperties = PropertyReservation::where('tenant_id',Auth::user()->id)
        ->where('isReserved',true)
        ->get();

        $data['status'] = 'Success';
        $data['message'] = 'Property Reserved Successfully';
        $data['reservedProperties'] = $reservedProperties;
        return response()->json($data, 200);
    }

    //fetch single reservation
    public function fetchSingleReservation($id)
    {
        $reservation = PropertyReservation::find($id);

        $data['status'] = 'Success';
        $data['message'] = 'Property Reserved Successfully';
        $data['reservation'] = $reservation;
        return response()->json($data, 200);
    }


    //**********************LANDLORD ENDPOINTS ON PROPERTY RESERVATIONS */

    //fetch reservation for landlord
    public function landlordReservationsList(){
        $reservations = PropertyReservation::all();
        return $reservations;
    }

    

}
