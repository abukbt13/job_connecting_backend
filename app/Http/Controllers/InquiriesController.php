<?php

namespace App\Http\Controllers;

use App\Models\Inquire;
use Illuminate\Http\Request;

class InquiriesController extends Controller
{

    public function inquire(Request $request) {

        $userAgent = $request->header('User-Agent');
        // Use a library like jenssegers/agent to parse the User-Agent string
        $agent = new \Jenssegers\Agent\Agent();
        // Detect the platform using the parsed User-Agent string
        $platform = $agent->platform();

        $inguire = new Inquire();
        $inguire->name = $request->name;
        $inguire->email = $request->email;
        $inguire->message = $request->message;

        storelog('inquiry',$inguire, $platform);
        if($inguire->save()){
            return response([
                'status'=>'success',
                'message'=>'Messsage sent successfully'
            ]);
        }
        else{
            return response([
                'status'=>'failed',
                'message'=>'something went wrong'
            ]);
        }

    }

}
