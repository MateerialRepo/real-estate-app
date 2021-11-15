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
    public function fetchAllTenantDocument()
    {
        $user = Auth::user();

        $documents = Document::where('tenant_id', '=', $user->id)
            ->orderBy('created_at', 'desc')->get();

        $data['status'] = 'Success';
        $data['message'] = 'Tickets Fetched Successfully';
        $data['data'] = $documents;
        return response()->json($data, 200);
    }

    // fetch single document
    public function fetchSingleDocument($unique_id)
    {
        try {

            $document = Document::where('document_unique_id', $unique_id)->first();

            if (!$document) {
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
    public function createDocument(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'document_file' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            };

            $unique_id = 'DOC-' . Str::random(7) . time() . '-BRC';

            $user = Auth::user();

            $document_unique_id = $unique_id;

            $document_format = $request->document_file->extension();

            $document = time() . rand(1000000, 9999999) . '.' . $document_format;

            $documentURL = env('APP_URL') . '/tenants/documents/' . $document;

            $request->document_file->move(public_path('/tenants/documents'), $document);


            $document = Document::create(
                [
                    'tenant_id' => $user->id,
                    'document_unique_id' => $document_unique_id,
                    'document_category' => $request->document_category,
                    'document_url' => $documentURL,
                    'document_format' => $document_format,
                    'description' => $request->description,
                    'landlord_id' => $request->landlord_id
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


    //update document
    public function updateDocument(Request $request, $unique_id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'document_file' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            };

            $document_data =  $request->all();


            if ($request->has('document_file')) {

                $document_format = $request->document_file->extension();

                $document = time() . rand(1000000, 9999999) . '.' . $document_format;

                $documentURL = env('APP_URL') . '/tenants/documents/' . $document;

                $request->document_file->move(public_path('/tenants/documents'), $document);

                $document_data['document_format'] =  $document_format;
                $document_data['document_url'] =  $documentURL;
            }

            $tenant = Auth::user();
            $document_data['tenant_id'] = $tenant->id;
            $document_data['document_unique_id'] = 'DOC-' . Str::random(7) . time() . '-BRC';

            $document = Document::where('document_unique_id', $unique_id)->update($document_data);

            $data['status'] = 'Success';
            $data['message'] = 'Document updated Successfully';
            $data['data'] = $document;
            return response()->json($data, 200);
        } catch (\Exception $exception) {

            $data['status'] = 'Failed';
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }

    //delete document
    public function deleteDocument($unique_id)
    {
        try {

            $document = Document::where('document_unique_id', $unique_id)
                ->first();

            if (!$document) {
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
