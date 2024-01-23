<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function post_job()
    {
        $rules = [
            'description' => 'required',
            'payment' => 'required',
            'skills' => 'required',
            'priority' => 'required',
            'payment_amount' => 'required',
        ];
        $data = request()->all();
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())){
            return response([
                'status' => 'failed',
                'error' => $valid->errors()
            ]);
        }
        $post_id=Auth::User()->id;
        $post = new Post();

        $post->description = $data['description'];
        $post->payment = $data['payment'];
        $post->priority = $data['priority'];
        $post->payment_amount = $data['payment_amount'];
        $post->user_id = $post_id;
        $post->save();
        return response([
        'message'=>'Post successfully created',
        'posts'=>$post
    ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show_post()
    {
        //
    }

}
