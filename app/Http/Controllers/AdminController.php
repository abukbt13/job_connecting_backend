<?php

namespace App\Http\Controllers;

use App\Models\Connect;
use App\Models\Inquire;
use App\Models\Log;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function messages(){
        $messages = Inquire::all();
        return response([
            'status'=>'success',
            'message'=>$messages
        ]);
    }
    public function show_logs(){
        $show_logs = Log::all();
        return response([
            'status'=>'success',
            'logs'=>$show_logs
        ]);
    }
    public function connects(){
        $connects = Connect::all();
        return response([
            'status'=>'success',
            'connects'=>$connects
        ]);
    }
    public function users(){
        $users = User::all();
        return response([
            'status'=>'success',
            'users'=>$users
        ]);
    }
}
