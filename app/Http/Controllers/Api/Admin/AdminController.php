<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Admin;
use App\Models\Tenant;
use App\Models\Ticket;
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
        $admins = Admin::all()->orderBy('created_at', 'desc')->get();
        return response()->json($admins);
    }

    public function show($id)
    {
        $admin = Admin::find($id);
        return response()->json($admin);
    }

    //*********************************Create admin users with role*************************************************//

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

    //*********************************Handling tenants activities*************************************************/ 
    public function allTenants()
    {
        try{

            $data['status'] = 'Success';
            $data['message'] = 'Tenants retrieved successfully';
            $data['data'] = Tenant::all()->orderBy('created_at', 'desc')->get();
            return response()->json($data, 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'No Tenants not found'], 404);

        }
        
    }

    public function singleTenant(Tenant $tenant, $id)
    {
        try{

            $data['status'] = 'Success';
            $data['message'] = 'Tenant retrieved successfully';
            $data['data'] = $tenant->find($id);
            return response()->json($data, 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'No Tenant not found'], 404);

        }
        
    }

    public function destroyTenant(Tenant $tenant, $id)
    {

        $tenant->delete();

        return response()->json(['message' => 'Tenant deleted successfully']);
    }



    //*********************************Handling Landlord activities/functions******************************************//
    public function allLandlords()
    {
        try {

            $data['status'] = 'Success';
            $data['message'] = 'Landlords retrieved successfully';
            $data['data'] = Landlord::all()->orderBy('created_at', 'desc')->get();
            return response()->json($data, 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'No Landlords not found'], 404);

        }
    }


    public function singleLandlord(Landlord $landlord, $id)
    {
        try{

            $data['status'] = 'Success';
            $data['message'] = 'Landlord retrieved successfully';
            $data['data'] = $landlord->find($id);
            return response()->json($data, 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Landlord not found'], 404);

        }
    }


    public function destroyLandlord($id)
    {
        $landlord = Landlord::find($id);
        $landlord->delete();

        return response()->json(['message' => 'Landlord deleted successfully']);
    }




    //*********************************Handling properties activities and functions************************************//

    public function allProperties()
    {
        try{
            $data['status'] = 'Success';
            $data['message'] = 'Properties retrieved successfully';
            $data['data'] = Property::all()->orderBy('created_at', 'desc')->get();
            return response()->json($data, 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'No Properties not found'], 404);

        }
        
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


    //*********************************Handling ticket activities and functions************************************//

    public function allTickets()
    {
        $data['status'] = 'Success';
        $data['message'] = 'Tickets retrieved successfully';
        $data['data'] = Ticket::all()->orderBy('created_at', 'desc');
        return response()->json($data, 200);
    }


    public function singleTicket(Ticket $ticket, $id)
    {
        $data['status'] = 'Success';
        $data['message'] = 'Ticket retrieved successfully';
        $data['data'] = $ticket->find($id);
        return response()->json($data, 200);
    }

    //comment on ticket
    public function commentOnTicket(Ticket $ticket, $id, Request $request)
    {
        $ticket = $ticket->find($id);
        $ticket->comments()->create([
            'comment' => $request->comment,
            'user_id' => $request->user_id,
        ]);

        return response()->json(['message' => 'Comment added successfully']);
    }

    //assign ticket to user
    public function assignTicket(Ticket $ticket, $id, Request $request)
    {
        $ticket = $ticket->find($id);
        $ticket->landlord_id = $request->user_id;
        $ticket->save();

        return response()->json(['message' => 'Ticket assigned successfully']);
    }

}
