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
    public function connect_employer()
    {

        $rules = [
            'employer_id' => 'required',
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
        $checkexistence = Connect::where('job_seeker_id', $user_id)->where ('employer_id' ,$data['employer_id'])->count();
        if($checkexistence >0){
            return response([
                'status'=>'failed',
                'message'=>'Connection has been established already',
            ]);
        }
        else{
            $mpesa = new MpesaRepository();
            $mpesa->C2BMpesaApi();
            $connect = new Connect();
            $connect->job_seeker_id=$user_id;
            $connect->employer_id=$data['employer_id'];

            $connect->save();

            return response([
                'status'=>'success',
                'message'=>'Connection established successfully',
                'referees'=>$connect
            ]);
        }
    }
    public function connect_job_seeker()
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
        $employer_id = $data['job_seeker_id'];
        $check_connect_exist = Connect::where('employer_id', $user_id)
            ->where('job_seeker_id', $employer_id)
            ->first();

        if ($check_connect_exist) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Connection already established'
            ]);
        }


        $connect = new Connect();
        $connect->job_seeker_id=$employer_id;
        $connect->employer_id=$user_id;
        $connect->save();

        return response([
            'status'=>'success',
            'message'=>'Connection established successfully',
        ]);


    }

}
