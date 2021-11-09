<?php

namespace App\Http\Controllers\Api\Property;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PropertyVerification;

class PropertyVerificationController extends Controller
{
    ///Landlord property verification
    public function verifyProperty(Request $request, $id)
    {
        $documentPath=[];
        if($request->has('property_document')){

            foreach($request->file('property_document') as $key => $document){
                $fileName = $document->getClientOriginalName();
                $document->move(public_path('/properties/propertyDocuments'), $fileName);
                $filepath = env('APP_URL').'/properties/propertyDocuments/'.$fileName;
                $documentPath[$key] = $filepath;
            }
        }

        $propertyVerification = PropertyVerification::create([
            'property_id' => $id,
            'verification_type' => $request->verification_type,
            'property_document' => $documentPath,
            'description' => $request->description,
        ]);


        $property = Property::find($id);
        $property->is_verified = true;
        $property->save();

        $data['status'] = 'Success';
        $data['message'] = 'Property Successfully Verified';
        return response()->json($data, 201);
    }
}
