<?php

namespace App\Http\Controllers\Api\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    //create or Update Ticket
    public function createAndUpdate(Request $request){
        try{

            $validator = Validator::make($request->all(), [
                'ticket_title' => 'required|max:255', 
                'ticket_category' => 'required|string', 
                'description' => 'required|string',
                'ticket_img.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:2048', 
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            };

            $ticket_unique_id = 'TKT-'.Str::random(7).'-'.time();;

            $ticket_collection=[];

            if($request->has('ticket_img')){

                foreach($request->file('ticket_img') as $ticket){
                    
                    $ticketimage = time().rand(1,100).'.'.$ticket->extension();
                    $ticket->move(public_path('/tenants/ticketimages'), $ticketimage);
                    $ticketURL = '/tenants/ticketimages/'.$ticketimage;
                    $ticket_collection[] = $ticketURL;
                };

            };

            $user = Auth::user();

            $ticket = Ticket::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'ticket_unique_id' => $ticket_unique_id,
                    'ticket_status' => 'Open', 
                    'ticket_title' => $request->ticket_title, 
                    'ticket_category' => $request->ticket_category, 
                    'description' => $request->description,
                    'ticket_img' => json_encode($ticket_collection), 
                    'assigned_id' => $request->assigned_id
                ]
            );
            
            $data['status'] = 'Success';
            $data['message'] = 'Ticket Created Successfully';
            return response()->json($data, 200);

       } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);

       }
    }



    public function resolveTicket($unique_id){
        try{
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

    public function reopenTicket($unique_id){
        try{
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
    public function fetchAll(){
        try{

            $user = Auth::user();
            $tickets = Ticket::where('user_id','=', $user->id)
                        ->orWhere('assigned_id','=', $user->id)
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
    public function fetchSingle($unique_id){
        try{
            $user = Auth::user();

            $ticket = Ticket::where('ticket_unique_id', $unique_id)
                        ->with('user', 'ticketComment')->get();

            if(!$ticket){
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

    // comment on ticket
    public function ticketComment(Request $request, $id){
        try{

            $validator = Validator::make($request->all(), [
                'comment' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
            
            $user = Auth::user();

            TicketComment::create([
                'ticket_id' => $id,
                'user_id' => $user->id,
                'comment' => $request->comment
            ]);

            $data['status'] = 'Success';
            $data['message'] = 'Comment Created Successfully';
            return response()->json($data, 200);

        } catch (\Exception $exception) {
            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }

    public function deleteTicket($unique_id){
        try{

            $user = Auth::user();
            $ticket = Ticket::where('ticket_unique_id', $unique_id)
                        ->with('user', 'ticketComment')->get();

            if(!$ticket){
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
