<?php

namespace App\Http\Controllers;

use App\Models\Capture;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function capture(){
        $content = "{"Body":{"stkCallback":{"MerchantRequestID":"5220-40e9-bb16-c78ea820338d11816350","CheckoutRequestID":"ws_CO_02042024170008357715990366","ResultCode":0,"ResultDesc":"The service request is processed successfully.","CallbackMetadata":{"Item":[{"Name":"Amount","Value":1.00},{"Name":"MpesaReceiptNumber","Value":"SD25WSR9W7"},{"Name":"Balance"},{"Name":"TransactionDate","Value":20240402170017},{"Name":"PhoneNumber","Value":254715990366}]}}}}";
        $data = json_decode($content, true);


        if (!is_array($data)) {
            $payment = new Capture();
            $payment->data = $content;

            $payment->save();
        }

// Assuming Capture is your model class
        $payment = new Capture();
        $payment->data = $data;

       $payment->save();
    }

}
