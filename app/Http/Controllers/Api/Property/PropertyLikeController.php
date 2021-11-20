<?php

namespace App\Http\Controllers\Api\Property;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PropertyLike;
use Illuminate\Support\Facades\Auth;

class PropertyLikeController extends Controller
{
    //tenant like a property
    public function likeProperty(Request $request, $propertyId)
    {
        PropertyLike::create([
            'tenant_id' => Auth::user()->id,
            'property_id' => $propertyId,
            'isLiked'=> true
        ]);

        $data['status'] = 'Success';
        $data['message'] = 'Liked Property Successfully';
        return response()->json($data, 200);
    }

    //tenant unlike a property
    public function unlikeProperty(Request $request, $propertyId)
    {
        $propertyLike = PropertyLike::where('tenant_id', Auth::user()->id)
            ->where('property_id', $propertyId)
            ->first();

        $propertyLike->delete();

        $data['status'] = 'Success';
        $data['message'] = 'Property Unliked Successfully';
        return response()->json($data, 200);
    }
}
