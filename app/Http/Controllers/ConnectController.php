<?php

namespace App\Http\Controllers;

use App\Models\Connect;
use App\Models\Referee;
use App\Repositories\MpesaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConnectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function connect_employer(Request $request)
    {

        $rules = [
            'employer_id' => 'required',
            'phone' => 'required',
        ];
        $data = request()->all();
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())){
            return response([
                'status' => 'failed',
                'error' => $valid->errors()
            ]);
        }
        $phone=$request->phone;
        $user=Auth::User();
        $user_id=$user->id;
        $checkexistence = Connect::where('job_seeker_id', $user_id)->where ('employer_id' ,$data['employer_id'])->count();
        if($checkexistence >0){
            return response([
                'status'=>'failed',
                'message'=>'Connection has been established already',
            ]);
        }
        else{

            $job_seeker_id=$user_id;
            $employer_id=$data['employer_id'];
//            $mpesa = new MpesaRepository();
//            $mpesa->C2BMpesaApi($job_seeker_id,$employer_id,$phone);
            $connect = new Connect();
            $connect->job_seeker_id=$job_seeker_id;
            $connect->employer_id=$employer_id;
            $connect->receipt_no='vhjvhgdredh';
            $connect->save();
            return response([
                'status'=>'success',
                'message'=>'Connection has been established already',
            ]);
        }
    }
    public function connect_job_seeker(Request $request)
    {

        $rules = [
            'job_seeker_id' => 'required',
        ];
        $data = request()->all();
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())){
            return response([
                'status' => 'failed',
                'error' => $valid->errors()
            ]);
        }
        $user_id=Auth::User()->id;
        $phone=$request->phone;
        $job_seeker_id = $data['job_seeker_id'];
        $employer_id = $user_id;

        $checkexistence = Connect::where('employer_id', $employer_id)->where ('job_seeker_id' ,$job_seeker_id)->count();

        if ($checkexistence>0) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Connection already established'
            ]);
        }
//            $mpesa = new MpesaRepository();
//            $mpesa->C2BMpesaApi($job_seeker_id,$employer_id,$phone);
        $connect = new Connect();
        $connect->job_seeker_id=$employer_id;
        $connect->employer_id=$user_id;
        $connect->receipt_no='vhjvhgdredh';
        $connect->save();

        return response([
            'status'=>'success',
            'message'=>'Connection established successfully',
        ]);


    }

}
