<?php

namespace App\Http\Controllers\Api\Document;

use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    // fetch all documents
    public function fetchAllDocument(){
        $landlord = Auth::guard('landlord')->user();

        $documents = Document::where('user_id','=', $landlord->id)
                    ->orWhere('assigned_id','=', $landlord->id)
                    ->orderBy('created_at', 'desc')->get();

        $data['status'] = 'Success';
        $data['message'] = 'Tickets Fetched Successfully';
        $data['data'] = $documents;
        return response()->json($data, 200);
    }

    // fetch single document
    public function fetchSingleDocument($unique_id){
        try{

            $landlord = Auth::guard('landlord')->user();


            $document = Document::where('document_unique_id', $unique_id);

            if(!$document){
                $data['status'] = 'Failed';
                $data['message'] = 'Document not found';
                return response()->json($data, 404);
            }

            $data['status'] = 'Success';
            $data['message'] = 'Document Fetched Successfully'; 
            $data['data'] = $document;
            return response()->json($data, 200);

        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }

    }

    //create document
    public function createAndUpdate(Request $request){
        try{
            
            $validator = Validator::make($request->all(), [
                'document_file' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            };

            $unique_id = 'DOC-'.Str::random(7).'-'.time();

            $user = Auth::user();
        
            $document_unique_id = $unique_id;
            
            $document_format = $request->document_file->extension();

            $document = time().rand(1,1000).'.'.$document_format;

            $documentURL = '/tenants/documents/'.$document;

            $request->document_file->move(public_path('/tenants/documents'), $document);
            

            $document = Document::updateOrCreate(
                ['user_id' => $user->id], 
                [
                    'document_unique_id' => $document_unique_id,
                    'document_category' => $request->document_category, 
                    'document_url' => $documentURL, 
                    'document_format' => $document_format, 
                    'description' => $request->description,
                    'assigned_id' => $request->assigned_id
                ]
            );


            $data['status'] = 'Success';
            $data['message'] = 'Document Created Successfully';
            $data['data'] = $document;
            return response()->json($data, 200);

        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }

    //delete document
    public function deleteDocument($id){
        try{

            $user = Auth::user();
            $document = Document::where('id',$id)
                    ->where('user_id', $user->id)
                    ->first();

            if(!$document){
                $data['status'] = 'Failed';
                $data['message'] = 'Document not found';
                return response()->json($data, 404);
            };

            $document->delete();

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
