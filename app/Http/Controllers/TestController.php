<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{

    public function update(Request $request)
    {
        $user = Auth::user();
//
//        if ($user) {
            $user_id = $user->id;
            return $user_id;
//        } else {
//            // Handle the case where the user is not authenticated
//            dd("User not authenticated");
//        }


    }
}
