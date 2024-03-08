<?php

namespace App\Http\Controllers;

use App\Models\Connect;
use App\Models\Referee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConnectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function connect()
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
    public function connect_employer()
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
        $connect = new Connect();
        $connect->job_seeker_id=$data['job_seeker_id'];
        $connect->employer_id=$user_id;
        $connect->save();

        return response([
            'status'=>'success',
            'message'=>'Connection established successfully',
            'referees'=>$connect
        ]);


    }

}
