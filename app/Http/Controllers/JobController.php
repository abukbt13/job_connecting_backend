<?php

namespace App\Http\Controllers;

use App\Models\Referee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function show()
    {
        $job_seekers = User::where('role', 'job_seeker')->get();
        return response([
            'status' => 'success',
            'message' => 'Job seekers successfully fetched',
            'data' => $job_seekers
        ]);
    }
}
