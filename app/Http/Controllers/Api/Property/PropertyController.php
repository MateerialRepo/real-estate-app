<?php

namespace App\Http\Controllers\Api\Property;

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
    public function getProperty($unique_id)
    {
        $property = Property::where('property_unique_id',$unique_id)->get();
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
                    $imageName = "property".time().$key.'.'.$image->getClientOriginalExtension();

                    $image->move(public_path('/properties/propertyImages'), $imageName);
                    $imageslink[$key] = env('APP_URL').'/properties/propertyImages/'.$imageName;

                };

            };


            $landlord = Auth::guard('landlord')->user();
            $property_data = $request->all();
            $property_data['landlord_id'] = $landlord->id;
            $property_data['property_unique_id'] = "PRP-".mt_rand(10000000,99999999)."-BRC";
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


    //save property
    public function updateProperty(Request $request, $unique_id)
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


                $landlord = Auth::guard('landlord')->user();
                $property_data = $request->all();
                $property_data['landlord_id'] = $landlord->id;
                $property_data['property_images'] = $imageslink;
                $property_data['property_amenities'] = json_encode($request->property_amenities);

                Property::where('property_unique_id',$unique_id)->update($property_data);
                $data['status'] = 'Success';
                $data['message'] = 'Property Successfully Updated';
                $data['data'] = Property::where('property_unique_id',$unique_id)->get();
                return response()->json($data, 200);

            } else {

                $landlord = Auth::guard('landlord')->user();
                $property_data = $request->all();
                $property_data['landlord_id'] = $landlord->id;
                $property_data['property_amenities'] = json_encode($request->property_amenities);

                $property = Property::where('property_unique_id',$unique_id)->get;;
                $property->update($property_data);
                $data['status'] = 'Success';
                $data['message'] = 'Property Successfully Updated';
                $data['data'] = $property;
                return response()->json($data, 200);

            }
        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }


    //delete property
    public function deleteProperty($unique_id)
    {
        try{
            Property::where('property_unique_id',$unique_id)->delete();
            $data['status'] = 'Success';
            $data['message'] = 'Property Successfully Deleted';
            return response()->json($data, 200);
        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }
}

