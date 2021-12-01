<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Admin;
use App\Models\Tenant;
use App\Models\Landlord;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\PropertyReservation;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminRequest;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return response()->json($admins);
    }

    public function show($id)
    {
        $admin = Admin::find($id);
        return response()->json($admin);
    }

    // Create admin users with role
    public function createAdminUser(CreateAdminRequest $request)
    {
        $admin = Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'Admin user created successfully',
            'admin' => $admin
        ], 201);
    }


    public function deleteAdminUser($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json([
                'message' => 'Admin user not found'
            ], 404);
        }

        $admin->delete();
        return response()->json([
            'message' => 'Admin user deleted successfully'
        ], 200);
    }   

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


    public function destroyLandlord($id)
    {
        $landlord = Landlord::find($id);
        $landlord->delete();

        return response()->json(['message' => 'Landlord deleted successfully']);
    }

    // Handling properties activities and functions
    public function allProperties()
    {
        $data['status'] = 'Success';
        $data['message'] = 'Properties retrieved successfully';
        $data['data'] = Property::all();
        return response()->json($data, 200);
    }

    public function singleProperty(Property $property, $id)
    {
        $data['status'] = 'Success';
        $data['message'] = 'Property retrieved successfully';
        $data['data'] = $property->find($id);
        return response()->json($data, 200);
    }

    public function destroyProperty(Property $property, $id)
    {
        $property->delete();

        return response()->json(['message' => 'Property deleted successfully']);
    }

    public function allReservations()
    {
        $data['status'] = 'Success';
        $data['message'] = 'Reservations retrieved successfully';
        $data['data'] = PropertyReservation::all()->orderBy('created_at', 'desc');
        return response()->json($data, 200);
    }
}
