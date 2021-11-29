<?php

namespace App\Http\Controllers\Api\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        // dd($paymentDetails);

        // save the data in the transaction table
        $transaction = new Transaction();
        $transaction->tenant_id = Auth::user()->id;
        $transaction->property_id = $paymentDetails['data']['metadata']['property_id'];
        $transaction->amount = $paymentDetails['data']['amount']/100;
        $transaction->description = $paymentDetails['data']['reference'];
        $transaction->status = $paymentDetails['data']['status'];
        $transaction->save();

        $data['status'] = 'Success';
        $data['message'] = 'Payment successful';
        $data['data'] = $paymentDetails['data'];
        return response()->json($data, 200);

        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
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
}
