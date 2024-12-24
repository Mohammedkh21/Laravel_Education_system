<?php

namespace App\Services\Admin\Request;

use App\Models\Camp;
use App\Models\Request;
use App\Models\Student;
use App\Notifications\joinCampResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class RequestService
{

    function getAll()
    {
        $camps_id = auth()->user()->camps()->get()->pluck('id');
        $results = Request::where('type', 'join_camp')
            ->where(function($query) use ($camps_id) {
                foreach ($camps_id as $camp_id) {
                    $query->orWhereJsonContains('data->camp_id', $camp_id);
                }
            })
            ->with('requestable')->get();
        return $results;
    }

    function reply($request , $status)
    {
        $admin = auth()->user();
        $camp = Camp::findOrFail($request->data->camp_id);
        Gate::authorize('access', $camp);

        $user = $request->requestable;
        $model = $request->data->model ;

        if ( filter_var($status, FILTER_VALIDATE_BOOLEAN) ){
            $subscriptionPlan = $admin->subscriptionPlan;
            if ($model == Student::class){
                $student_limit = $subscriptionPlan->students;

                if ($student_limit == 0){
                    throw_if($admin->students()->count() >= $student_limit,
                        \Exception::class ,
                        "you already reached the limit of your subscription plan : $student_limit student",
                        401
                    );
                }
                $student = $user;
                if ($student->camp_id){
                    $request->delete();
                    throw_if($student->camp_id,\Exception::class,'the student joined another camp ',403);
                }
                $student->update(['camp_id'=>$request->data->camp_id]);
            }else{
                $teacher = $user;
                $teacher_limit = $subscriptionPlan->teachers;
                if ($teacher_limit == 0){
                    throw_if($admin->teachers()->count() >= $teacher_limit,
                        \Exception::class,
                        "you already reached the limit of your subscription plan : $teacher_limit teacher",
                        401
                    );
                }
                $teacher->camps()->attach($request->data->camp_id);
                $request->delete();
            }
        }

        Notification::send($user,new joinCampResponse($camp,$status));
        return $request->delete();
    }

}
