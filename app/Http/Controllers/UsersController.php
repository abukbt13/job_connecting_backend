<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\resetPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
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
        $user = new User();
        $user->email = $data['email'];
        $user->firstName = $data['firstName'];
        $user->lastName = $data['lastName'];
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        $user->password = Hash::make($request->password);
        $user->save();
//        storelog('Sign in', $user,'Linux');
        if (Auth::attempt(['email' => $data['email'], 'password' => $data ['password']])) {
            $token = $user->createToken('token')->plainTextToken;
            return response([
                'status'=>'success',
                'token'=>$token,
                'user'=>$user

            ]);

        }
    }
    public function login(Request $request)
    {
        $data = request()->all();
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ],422);
        }
        $email = request('email');
        $password = request('password');
        $user = User::where('email', $email)->get()->first();

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $token = $user->createToken('token')->plainTextToken;

            return response([
                'status' => 'success',
                'token' => $token,
                'user' => request()->user()
            ]);
        }
        else{
            return response([
                'status' => 'failed',
                'message' => 'Enter correct details',
            ]);
        }
    }

    public function auth(){
        if (Auth::check()) {
            return response()->json(['authenticated' => true]);
        } else {
            return response()->json(['authenticated' => false]);
        }
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $rules = [
            'firstName' => 'required',
            'lastName' => 'required',
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
        $user=User::where('id','=',$user_id)->get()->first();
        $user->firstName = $data['firstName'];
        $user->lastName = $data['lastName'];
        $user->phone = $data['phone'];
        $user->update();
        return response([
            'status' => 'success',
            'message' => 'Successfully updated',
            'data' => $user
        ]);

//
    }
//    public function reset_password(Request $request){
//        $OTP=rand();
//
//        $email=$request->email;
//        $user=User::where('email','=',$email)->get()->first();
//        $user->otp=99999;
//
//        $user->update();
//        $data = [
//            'subject' => 'Reset Password message',
//            'body' => 'Reset Password',
//            'otp' => $OTP
//        ];
//        if ($user){
//            try {
//                Mail::to($email)->send(new resetPassword($data));
//                return response()->json([
//                    'status'=>'success',
//                    'message'=>'Password reset sent successfully open your email account to reset your password'
//                ]);
//
//            }catch (Exception $th){
//                return response()->json([
//                    'status' =>'failed',
//                    'error'=>'Email does not exist'
//                ]);
//            }
//
//
//        }
//        else{
//            return response()->json([
//                'status'=>'fail',
//                'message'=>'Email not found'
//            ]);
//        }
//
//    }
//    public function change_password(Request $request,$id)
//    {
//        $user=User::where('otp','=',$id)->get()->first();
//        $user->password = Hash::make($request->password);
//        $user->update();
//        return response()->json([
//            'status'=>'success',
//            'message'=>'Password changed successfully'
//        ]);
//    }
//    public function updateStatus(){
//        $user_id=Auth::user();
//        $user_id=$user_id->id;
//        $user = User::find($user_id);
//        $user->status = 'applied';
//        $user->update();
//
//        return response([
//            'status'=>'success',
//            'message'=>'Job applied successfully'
//        ]);
//    }


}
