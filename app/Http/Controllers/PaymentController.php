<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function C2BMpesaApi(Request $request){

        $timestamp =now()->format('YmdHis');
        $password = base64_encode(env('C2B_SHORTCODE').env('PASSKEY').$timestamp);
        $curl_post_data = array(
            //Fill in the request parameters with valid values
                'BusinessShortCode' =>env('C2B_SHORTCODE'),
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => round(10.999,0),
                'PartyA' => '+254728548760',
                'PartyB' => env('C2B_SHORTCODE'),
                'PhoneNumber' => '+254728548760',
                'CallBackURL' =>  url('api/complete-payment/process'),
                'AccountReference' => env('C2B_SHORTCODE'),
                'TransactionDesc' => "Transaction for payment ID #".env('C2B_SHORTCODE')
        );
        $data_string = json_encode($curl_post_data);
        $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $headers = array('Content-Type:application/json','Authorization:Bearer '.$this->getAccessToken()->access_token);
        $res = $this->doCurl($url,$data_string,'POST',$headers);
        return $res;
    }

}
