<?php

namespace App\Http\Controllers;

use App\Models\Connect;
use App\Models\Inquire;
use App\Models\Referee;
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
            'role' => 'required',
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
        $user->role = $data['role'];
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
    public function more($id){
        $user = User::find($id);
        return response([
            'status'=>'success',
            'user'=>$user
        ]);
    }
    public function refs($id){
       $refs = Referee::where('user_id',$id)->get();
       $count = Referee::where('user_id',$id)->count();
        return response([
            'status'=>'success',
            'refs'=>$refs,
            'count'=>$count
        ]);
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
        $user->county = $data['county'];
        $user->sub_county = $data['sub_county'];
        $user->gender = $data['gender'];

        if($request->hasFile('picture')){
            $picture = $request->file('picture');
            $pictureName = time() . '_' .  $picture->getClientOriginalName();
//        $picture->storeAs('Profiles', $pictureName, 'public');
            $picture->move(public_path('Profiles/'), $pictureName);
            $user->picture = $pictureName;
//            unlink(public_path('Profiles/' . $pictureName));
        }

        $user->update();
        return response([
            'status' => 'success',
            'message' => 'Successfully updated',
            'data' => $user
        ]);

//
    }
    public function e_details($id) {
            $user=User::find($id);
            return response([
                'status'=>'success',
                'user'=>$user
            ]);
    }
    public function j_details($id) {
            $user=User::find($id);
            return response([
                'status'=>'success',
                'user'=>$user
            ]);
    }
    public function forget_pass(){
        $rules = [
            'email' => 'required',
        ];
        $data = request()->all();
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())){
            return response([
                'status' => 'failed',
                'error' => $valid->errors()
            ]);
        }
        $email=$data['email'];
        $user = User::where('email',$email)->first();
        if ($user){
            $otp = rand(999,10000);
            $user->otp = $otp;
            $user->update();

            $to=$user->phone;

            $curl = curl_init();
            $message ="Use OTP : $otp to reset your password ";
            $data = array(
                'api_token' => env('API_TOKEN'),
                'from' => env('SENDER_NAME'),
                'to' => '+254'.$to,
                'message' => $message
            );

            curl_setopt_array($curl, array(
                CURLOPT_URL => env('CURLOPT_URL'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($data),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response, true);

            $status = $data['status'];
            if($status == 'success'){
                return response([
                    'status'=>'success'
                ]);
            }
            else{
                return response([
                    'failed'=>'Try again later'
                ]);
            }

        }



        else{
            return response([
                'status'=>'User not found'
            ]);
        }

    }
    public function reset_password(){
        $rules = [
            'email' => 'required',
            'otp' => 'required',
        ];
        $data = request()->all();
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())){
            return response([
                'status' => 'failed',
                'error' => $valid->errors()
            ]);
        }
        $email=$data['email'];
        $otp=$data['otp'];

        $user = User::where('email',$email)->where('otp',$otp)->first();
        if ($user){
            return response([
                'status'=>'success',
                'message' =>'Success you can change your password'
            ]);
        }
        else{
            return response([
                'status'=>'failed',
                'message' =>'Enter correct details '
            ]);
        }



    }
    public function finish_reset(){
        $rules = [
            'email' => 'required',
            'otp' => 'required',
            'password' => [
                'required',
                'min:8', // Enforce minimum password length of 6 characters
                'regex:/[A-Z]+/', // Ensure at least one uppercase letter
                'regex:/[!@#$%^&*()_+:\-=\[\]{};"\\|,.<>\/?]+/', // Ensure at least one symbol (excluding common delimiters)
            ],
        ];
        $data = request()->all();
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())){
            return response([
                'status' => 'failed',
                'message' =>'Ensure you enter correct details',
                'error' => $valid->errors()
            ]);
        }
        $email=$data['email'];
        $otp=$data['otp'];
        $password=$data['password'];
        $hah_password = hash('sha256',$password);
        $user = User::where('email',$email)->where('otp',$otp)->first();
        if ($user){

            $user->password = $hah_password;
            $user->update();
            return response([
                'status'=>'success',
                'message' =>'Password changed successfully'
            ]);
        }
        else{
            return response([
                'status'=>'failed',
                'message' =>'Ensure correct details are entered'
            ]);
        }



    }
    public function employers(){
        $employers = User::where('role', 'employer')->get();

            return response([
                'status'=>'success',
                'message' =>'Employers retrieved successfully',
                'employers' =>$employers
            ]);
        }
    public function employer_details($id){
        $employers = User::find($id);
        $employer_reference = Referee::where('user_id',$id)->get();
            return response([
                'status'=>'success',
                'message' =>'Employers retrieved successfully',
                'employers' =>$employers,
                'referee' =>$employer_reference
            ]);
        }


}
