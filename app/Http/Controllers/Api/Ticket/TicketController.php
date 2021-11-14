<?php

namespace App\Http\Controllers\Api\Ticket;

use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TicketComment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    //create Ticket
    public function createTicket(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'ticket_title' => 'required|max:255',
                'ticket_category' => 'required|string',
                'description' => 'required|string',
                'ticket_img.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
                'landlord_id' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            };


            $ticket_collection = [];

            if ($request->has('ticket_img')) {

                $images = $request->file('ticket_img');


                foreach ($images as $key => $image) {

                    $imageName = time() . rand(1000000, 9999999) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('/tenants/ticketimages'), $imageName);
                    $ticket_collection[$key] = env('APP_URL') . '/tenants/ticketimages/' . $imageName;
                };
            };


            $tenant = Auth::user();
            $ticket_data = $request->all();
            $ticket_data['tenant_id'] = $tenant->id;
            $ticket_data['ticket_img'] = $ticket_collection;
            $ticket_data['ticket_status'] = 'Open';
            $ticket_data['ticket_unique_id'] = "TKT-" . mt_rand(10000000, 99999999) . "-BRC";


            $ticket =  Ticket::create($ticket_data);

            $data['status'] = 'Success';
            $data['message'] = 'Ticket Created Successfully';
            $data['data'] = $ticket;
            return response()->json($data, 200);
        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }



    public function resolveTicket($unique_id)
    {
        try {
            $ticket = Ticket::where('ticket_unique_id', $unique_id)->first();
            $ticket->ticket_status = 'Resolved';
            $ticket->save();

            $data['status'] = 'Success';
            $data['message'] = 'Ticket Resolved Successfully';
            return response()->json($data, 200);
        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }

    public function reopenTicket($unique_id)
    {
        try {
            $ticket = Ticket::where('ticket_unique_id', $unique_id)->first();
            $ticket->ticket_status = 'Open';
            $ticket->save();

            $data['status'] = 'Success';
            $data['message'] = 'Ticket Reopened Successfully';
            return response()->json($data, 200);
        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }

    // Fetch all tickets
    public function fetchAll()
    {
        try {

            // $user = Auth::user();
            $tenant = Auth::guard('tenant')->user();
            $tickets = Ticket::where('tenant_id', $tenant->id)
                ->orderBy('created_at', 'desc')->get();

            $data['status'] = 'Success';
            $data['message'] = 'Tickets Fetched Successfully';
            $data['data'] = $tickets;
            return response()->json($data, 200);
        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }

    // Fetch single ticket
    public function fetchSingle($unique_id)
    {
        try {
            // $user = Auth::user();
            // $tenant = Auth::guard('tenant')->user();
            $ticket = Ticket::where('ticket_unique_id', $unique_id)->get();

            if (!$ticket) {
                $data['status'] = 'Failed';
                $data['message'] = 'Ticket not found';
                return response()->json($data, 404);
            }

            return response()->json($ticket, 200);
        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }


    public function deleteTicket($unique_id)
    {
        try {

            // $user = Auth::user();
            $ticket = Ticket::where('ticket_unique_id', $unique_id)->get();

            if (!$ticket) {
                $data['status'] = 'Failed';
                $data['message'] = 'Ticket not found';
                return response()->json($data, 404);
            };

            $ticket->delete();

            $data['status'] = 'Success';
            $data['message'] = 'Document Deleted Successfully';
            return response()->json($data, 200);
        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }
}
