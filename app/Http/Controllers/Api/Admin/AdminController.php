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
        try{

            $admins = Admin::orderBy('created_at', 'desc')->get();
            return response()->json($admins);

        } catch (\Exception $e) {

            $data['status'] = 'Failed';
            $data['error'] = $e->getMessage();
            return response()->json($data, 500);
        }

    }

    public function show($id)
    {
        try{

                $admin = Admin::find($id);
                return response()->json($admin);

            } catch (\Exception $e) {

                $data['status'] = 'Failed';
                $data['error'] = $e->getMessage();
                return response()->json($data, 500);
        }

    }

    //*********************************Create admin users with role*************************************************//

    public function createAdminUser(CreateAdminRequest $request)
    {
        try{
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Admin user creation failed',
                'error' => $e->getMessage()
            ], 500);
        }

    }


    public function deleteAdminUser($id)
    {
        try{
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
        catch(\Exception $e){
            $data['status'] = 'Failed';
            $data['error'] = $e->getMessage();
            return response()->json($data, 500);
        }

    }





    //*********************************Handling Landlord activities/functions******************************************//
    public function allLandlords()
    {
        try {
            $data['status'] = 'Success';
            $data['message'] = 'Landlords retrieved successfully';
            $data['data'] = Landlord::orderBy('created_at', 'desc')->get();
            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data['status'] = 'Failed';
            $data['error'] = $e->getMessage();
            return response()->json($data, 500);

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

            $data['status'] = 'Failed';
            $data['error'] = $e->getMessage();
            return response()->json($data, 500);

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
            $data['data'] = Property::orderBy('created_at', 'desc')->get();
            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data['status'] = 'Failed';
            $data['error'] = $e->getMessage();
            return response()->json($data, 500);

        }

    }

    public function singleProperty(Property $property, $id)
    {
        try{
                $data['status'] = 'Success';
                $data['message'] = 'Property retrieved successfully';
                $data['data'] = $property->find($id);
                return response()->json($data, 200);

        } catch (\Exception $e) {

                $data['status'] = 'Failed';
                $data['error'] = $e->getMessage();
                return response()->json($data, 500);
        }

    }


    public function destroyProperty(Property $property, $id)
    {
        $property->delete();

        return response()->json(['message' => 'Property deleted successfully']);
    }


    public function allReservations()
    {
        try{

            $data['status'] = 'Success';
            $data['message'] = 'Reservations retrieved successfully';
            $data['data'] = PropertyReservation::orderBy('created_at', 'desc')->get();
            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data['status'] = 'Failed';
            $data['error'] = $e->getMessage();
            return response()->json($data, 500);

        }

    }


    //*********************************Handling ticket activities and functions************************************//

    public function allTickets()
    {
        try{

            $data['status'] = 'Success';
            $data['message'] = 'Tickets retrieved successfully';
            $data['data'] = Ticket::orderBy('created_at', 'desc')->get();
            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data['status'] = 'Failed';
            $data['error'] = $e->getMessage();
            return response()->json($data, 500);

        }

    }


    public function singleTicket(Ticket $ticket, $id)
    {
        try{

            $data['status'] = 'Success';
            $data['message'] = 'Ticket retrieved successfully';
            $data['data'] = $ticket->find($id);
            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data['status'] = 'Failed';
            $data['error'] = $e->getMessage();
            return response()->json($data, 500);
        }

    }


    //assign ticket to user
    public function assignTicket(Ticket $ticket, $id, Request $request)
    {
        try{

            $ticket = $ticket->find($id);
            $ticket->landlord_id = $request->user_id;
            $ticket->save();

            return response()->json(['message' => 'Ticket assigned successfully']);

        } catch (\Exception $e) {

            $data['status'] = 'Failed';
            $data['error'] = $e->getMessage();
            return response()->json($data, 500);
        }

    }


    // Overview Page for Admin
    public function adminOverview(){
        // Total no of tickets
        $totalNoOfTickets = Ticket::count();

        // Total no of Opened Tickets
        $totalOpenTickets = Ticket::where('ticket_status','open')->count();

        // Total number of closed tickets
        $totalClosedTickets = $totalNoOfTickets - $totalOpenTickets;

        // Total no of properties
        $totalProperties = Property::count();

        // Total no of available Properties
        $totalAvailableProperties = Property::where('is_available', 'available')->count();

        // Total no of occupied properties
        $totalOccupiedProperties = $totalProperties - $totalAvailableProperties;

        // Total number of pending verified properties
        $noOfVerifiedProperties = Property::where('is_verified', 'verified')->count();

        // Total unverified Properties
        $totalUnverifiedProperties = $totalProperties - $noOfVerifiedProperties;

        $data['status'] = 'Success';
        $data['totalNoOfTickets'] = $totalNoOfTickets;
        $data['totalOpenTickets'] = $totalOpenTickets;
        $data['totalClosedTickets'] = $totalClosedTickets;
        $data['totalProperties'] = $totalProperties;
        $data['totalAvailableProperties'] = $totalAvailableProperties;
        $data['totalOccupiedProperties'] = $totalOccupiedProperties;
        $data['verifiedProperties'] = $noOfVerifiedProperties;
        $data['unverifiedProperties'] = $totalUnverifiedProperties;


        return response()->json($data, 200);


    }

}
