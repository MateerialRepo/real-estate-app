<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Tenant;
use App\Models\Landlord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    // Handling tenants activities
    public function allTenants()
    {
        $data['status'] = 'Success';
        $data['message'] = 'Tenants retrieved successfully';
        $data['data'] = Tenant::all();
        return response()->json($data, 200);
    }

    public function singleTenant(Tenant $tenant, $id)
    {
        $data['status'] = 'Success';
        $data['message'] = 'Tenant retrieved successfully';
        $data['data'] = $tenant->find($id);
        return response()->json($data, 200);
    }

    public function destroyTenant(Tenant $tenant, $id)
    {

        $tenant->delete();

        return response()->json(['message' => 'Tenant deleted successfully']);
    }




    // Handling Landlord activities/functions
    public function allLandlords()
    {
        $data['status'] = 'Success';
        $data['message'] = 'Landlords retrieved successfully';
        $data['data'] = Landlord::all();
        return response()->json($data, 200);
    }


    public function singleLandlord(Landlord $landlord, $id)
    {
        $data['status'] = 'Success';
        $data['message'] = 'Landlord retrieved successfully';
        $data['data'] = $landlord->find($id);
        return response()->json($data, 200);
    }   

    
    public function destroyLandlord(Landlord $landlord, $id)
    {

        $landlord->delete();

        return response()->json(['message' => 'Landlord deleted successfully']);
    }
}
