<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create_notification($employee_id)
    {
      $notification = new Notification();
      $user_id = Auth::user()->id;
      $check_exist = Notification::where('employer_id', $employee_id)->where('job_seeker_id', $user_id)
          ->where('j_status','inactive')->count();

      if($check_exist>0){
          return response([
              'status'=>'failed',
              'message'=>'Notification already created'
          ]);
      }
      else{
          $notification->job_seeker_id = $user_id;
          $notification->employer_id = $employee_id;
          $notification->j_status = 'inactive';
          $notification->e_status = 'active';
          $notification->save();

          return response([
              'status'=>'success',
              'message'=>'Notification was successfully created'
          ]);
      }

    }
    public function create_e_notification($job_seeker_id)
    {
      $notification = new Notification();
      $user_id = Auth::user()->id;
      $check_exist = Notification::where('job_seeker_id', $job_seeker_id)->where('employer_id', $user_id)
          ->where('e_status','inactive')->count();

      if($check_exist>0){
          return response([
              'status'=>'failed',
              'message'=>'Notification already created'
          ]);
      }
      else{
          $notification->job_seeker_id = $job_seeker_id;
          $notification->employer_id =$user_id;
          $notification->e_status = 'inactive';
          $notification->j_status = 'active';
          $notification->save();

          return response([
              'status'=>'success',
              'message'=>'Notification was successfully created'
          ]);
      }

    }




    public function e_notifications(Notification $notification)
    {
        $user_id = Auth::user()->id;
        $notification_count = Notification::where('employer_id', $user_id)->where('e_status','active')->count();
        $notification = Notification::where('employer_id', $user_id)
            ->join('users', 'users.id', '=', 'notifications.job_seeker_id')
            ->where('e_status', 'active')
            ->select('firstName','lastName','picture','county')
            ->get();
        return response([
            'status'=>'success',
            'count'=>$notification_count,
            'notification'=>$notification,
        ]);
    }
    public function j_notifications(Notification $notification)
    {
        $user_id = Auth::user()->id;
        $notification_count = Notification::where('job_seeker_id', $user_id)->where('j_status','active')->count();
        $notifications = Notification::where('job_seeker_id', $user_id)
            ->join('users', 'users.id', '=', 'notifications.employer_id')
            ->where('j_status', 'active')
            ->select('firstName','lastName','picture','county')
            ->get();
        return response([
            'status'=>'success',
            'count'=>$notification_count,
            'notification'=>$notifications,
        ]);
    }
}
