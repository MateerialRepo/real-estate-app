<?php

namespace App\Http\Controllers\Api\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaymentController extends Controller
{

    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway()
    {
        try{
            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e) {
            return Redirect::back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
        }        
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        dd($paymentDetails);

        // save the data in the transaction table
        

        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }

    public function callback()
    {
        // get reference  from request
        $reference = request('reference') ?? request('trxref');
        // verify payment details
        $payment = Paystack::transaction()->verify($reference)->response('data');
        dd($payment);
        // check payment status
        if ($payment['status'] == 'success') {
            // payment is successful
            dd($payment);
        } else {
            // payment is not successful
        }
    }


    //save transaction data
    public function saveTransaction(Request $request){

       

        // TenantID, Amount, PropertyID, Description
        $transaction = Transaction::create([
            "tenant_id" => $request->tenant_id,
            "property_id" => $request->property_id,
            "amount" => $request->amount,
            "description" => $request->description,
            "duration" => $request->duration
        ]);

        Property::where('id', $request->property_id)->update([
            'tenant_id'=>$request->tenant_id,
            'is_available'=> "occupied",
        ]);

        $data['status'] = 'Success';
        $data['message'] = 'Payment successful';
        $data['data'] = $transaction;
        return response()->json($data, 200);
    }



    // Get users transactions
    public function getTransactions(){
        $user = Auth::user();

        $transactions = Transaction::where('tenant_id', $user->id)->get();

        $data['status'] = "Success";
        $data['message'] = "Fetched all Tenant's transactions";
        $data['data'] = $transactions;

        return response()->json($data, 200);

    
    }


    //fetchall transasctions

    public function fetchAllTransactions(){
        $data['status'] = 'Success';
        $data['message'] = 'Transactions retrieved successfully';
        $data['transactions'] = Transaction::orderBy('created_at', 'desc')->get();
        return response()->json($data, 200);
        
    }

    // all landlord's transactions
    public function fetchAllLandlordTransactions(){
        $landlord = Auth::user();
        $properties = Property::where('landlord_id', $landlord->id)->get();
        $i = 0;
        foreach ($properties as $property) {
            $transaction[$i] = Transaction::where('property_id', $property->id)->orderBy('created_at', 'desc')->get();
            $i++;
        }

        $data['status'] = 'Success';
        $data['message'] = 'Transactions retrieved successfully';
        $data['transactions'] = $transaction;
        return response()->json($data, 200);
        
    }
}
