<?php

namespace App\Http\Controllers\Api\Landlord;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    //fetches all properties
    public function index()
    {
        $landlord = Auth::guard('landlord')->user();
        $properties = Property::where('landlord_id', $landlord->id)->get();
        $data['status'] = 'Success';
        $data['message'] = 'Fetched all properties Successfully';
        $data['properties'] = $properties;
        return response()->json($data, 200);
    }

    //fetches a single property
    public function getProperty($id)
    {
        $property = Property::find($id);
        $data['status'] = 'Success';
        $data['message'] = 'Fetched property Successfully';
        $data['property'] = $property;
        return response()->json($data, 200);
    }

    //save property
    public function createProperty(Request $request)
    {
        try{
            $imageslink=[];
            
            if($request->has('property_images')){

                $images = $request->file('property_images');

                foreach($images as $key=>$image){
                                     
                    // save each image to the server
                    $imageName = "property".time().'.'.$image->getClientOriginalExtension();

                    $image->move(public_path('/properties/propertyImages'), $imageName);
                    $imageslink[$key] = env('APP_URL').'/properties/propertyImages/'.$imageName;

                };

            };


            $landlord = Auth::guard('landlord')->user();
            $property_data = $request->all();
            $property_data['landlord_id'] = $landlord->id;
            $property_data['property_images'] = $imageslink;
            $property_data['property_amenities'] = json_encode($request->property_amenities);



            $property = Property::create($property_data);
            $data['status'] = 'Success';
            $data['message'] = 'Property Successfully Created';
            $data['data'] = $property;
            return response()->json($data, 200);

        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }



}
