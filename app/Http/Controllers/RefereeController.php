<?php

namespace App\Http\Controllers;

use App\Models\Referee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RefereeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rules = [
        'firstName' => 'required',
        'lastName' => 'required',
        'phone' => 'required',
        'relationship' => 'required',
        'county' => 'required',
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
        $referee = new referee();

        $referee->firstName = $data['firstName'];
        $referee->lastName = $data['lastName'];
        $referee->phone = $data['phone'];
        $referee->relationship = $data['relationship'];
        $referee->county = $data['county'];
        $referee->user_id = $user_id;
        $referee->save();
        return response([
            'status'=>'success',
            'message'=>'referee successfully created',
            'referees'=>$referee
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function view(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $referees = Referee::where('user_id', $user_id)->get();


        return response([
            'message' => 'Referees successfully fetched',
            'referees' => $referees
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Referee $referee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Referee $referee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Referee $referee)
    {
        //
    }
}
