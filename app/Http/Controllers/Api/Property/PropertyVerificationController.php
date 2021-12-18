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
        $documentPath = [];
        if ($request->has('property_document')) {

            foreach ($request->file('property_document') as $key => $document) {
                $fileName = time() . $key . $document->getClientOriginalName();
                $document->move(public_path('/properties/propertyDocuments'), $fileName);
                $filepath = env('APP_URL') . '/properties/propertyDocuments/' . $fileName;
                $documentPath[$key] = $filepath;
            }
        }

        PropertyVerification::create([
            'property_id' => $id,
            'document_type' => $request->document_type,
            'property_document' => $documentPath,
            'description' => $request->description,
        ]);


        $property = Property::find($id);
        $property->is_verified = "pending verification";
        $property->save();

        $data['status'] = 'Success';
        $data['message'] = 'Property Verification sent to Admin';
        return response()->json($data, 201);
    }


    // Admin property Verification
    public function adminVerifyProperty(Request $request, $id)
    {

        $property = Property::find($id);
        $property->is_verified = "verified";
        $property->save();

        $data['status'] = 'Success';
        $data['message'] = 'Property Verification Complete';
        return response()->json($data, 200);
    }
}
