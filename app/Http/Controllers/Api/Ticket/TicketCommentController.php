<?php

namespace App\Http\Controllers\Api\Ticket;

use Illuminate\Http\Request;
use App\Models\TicketComment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketCommentController extends Controller
{
    // comment on ticket
    public function ticketComment(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'comment' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $user = Auth::user();

            TicketComment::create([
                'ticket_id' => $id,
                'commenter_id' => $user->id,
                'comment' => $request->comment
            ]);

            $data['status'] = 'Success';
            $data['message'] = 'Comment Created Successfully';
            return response()->json($data, 200);
            
        } catch (\Exception $exception) {
            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 500);
        }
    }
}
