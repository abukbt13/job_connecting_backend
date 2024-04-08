<?php

namespace App\Http\Controllers;

use App\Models\Capture;
use App\Models\Connect;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    function capture(Request $request,$job_seeker_id,$employer_id){

            // Get the JSON data from the request using file_get_contents
            $json_data = file_get_contents('php://input');

            // Decode the JSON data
            $data = json_decode($json_data, true);

            // Access MpesaReceiptNumber and PhoneNumber
            $mpesa_receipt_number = $data['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
            $phone_number = $data['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];
            echo $phone_number;
            echo $mpesa_receipt_number;
            // Log the extracted data
            file_put_contents('log.txt', 'Mpesa Receipt Number: ' . $mpesa_receipt_number . "\n", FILE_APPEND);
            file_put_contents('log.txt', 'Phone Number: ' . $phone_number . "\n", FILE_APPEND);

                // You can perform further processing or return a response here
            $connect = new Connect();
            $connect->job_seeker_id=$job_seeker_id;
            $connect->employer_id=$employer_id;
            $connect->save();
        }

}



