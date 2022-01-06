<?php

namespace App\Http\Controllers\Api\Ticket;

use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SupportTicketRequest;

class SupportTicketController extends Controller
{

    // fetch single support ticket AVAILABLE FOR ALL
    public function getSingleSupportTicket(SupportTicket $supportTicket, $id){

        try{

            $supportTicket = $supportTicket->find($id);

            $data['status'] = 'Success';
            $data['message'] = 'Support Ticket Retrieved Successfully';
            $data['data'] = $supportTicket;
            return response()->json($data, 200);

        } catch (\Exception $exception) {
            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }

    }


    //Landlord support ticket creation
    public function createSupportTicket(SupportTicketRequest $request){

        try{

            $ticketData = $request->validated();
    
            $user = Auth::user();

            $support_files = [];

            if ($request->has('img')) {

                $images = $request->file('img');

                foreach ($images as $key => $image) {

                    $imageName = time() . rand(1000000, 9999999) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('/support'), $imageName);
                    $support_files[$key] = env('APP_URL') . '/support' . $imageName;
                };
            };

            $ticketData['user_id'] = $user->id;

            $ticketData['img'] = $support_files;

            // dd($data);

            SupportTicket::firstOrCreate($ticketData);
            
            $data['status'] = 'Success';
            $data['message'] = 'Support Ticket Created Successfully';
            return response()->json($data, 200);

        } catch (\Exception $exception) {
            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }

    }

    //Landlord support ticket list
    public function getLandlordSupportTicketList(){

        try{

            $user = Auth::user();

            $supportTickets = SupportTicket::where('user_type', 'landlord')
                                            ->where('user_id', $user->id)
                                            ->orderBy('created_at', 'desc')
                                            ->get();

            $data['status'] = 'Success';
            $data['message'] = 'Support Ticket List Retrieved Successfully';
            $data['data'] = $supportTickets;
            return response()->json($data, 200);

        } catch (\Exception $exception) {
            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }

    }


    // *****************************Admin support functions*****************************
    //fetches all support tickets admin end
    public function getSupportTickets(){

        try{

            $tickets = SupportTicket::orderBy('created_at', 'desc')->get();
            $data['status'] = 'Success';
            $data['tickets'] = $tickets;
            return response()->json($data, 200);

        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);

        }

    }

    // Send support mail to landlord(to fix eventually)
    // public function sendSupportMail(Request $request){

    //     try{

    //         $user = Auth::user();

    //         $supportTicket = SupportTicket::find($request->id);

    //         $landlord = $supportTicket->landlord;

    //         $landlord->notify(new \App\Notifications\SupportTicket($supportTicket));

    //         $data['status'] = 'Success';
    //         $data['message'] = 'Support Ticket Sent Successfully';
    //         return response()->json($data, 200);

    //     } catch (\Exception $exception) {

    //         $data['status'] = 'Failed';
    //         $data['message'] = $exception->getMessage();
    //         return response()->json($data, 400);

    //     }

    // }
}
