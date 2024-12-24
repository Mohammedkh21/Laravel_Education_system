<?php

namespace App\Services\Student;


use App\Models\Student;
use App\Notifications\joinCampRequest;
use Illuminate\Support\Facades\Notification;

class CampService
{
    function myCampInfo($user){
        return $user->camp;
    }

    function joinCamp($camp)
    {
        $user = auth()->user();
        if ($user->camp_id){
            throw new \Exception('you are already in a camp', 403);
        }
        $request =  $user->requests()->where('type', 'join_camp')
            ->whereJsonContains('data->camp_id', $camp->id)
            ->first();
        if($request){
            throw new \Exception('you are already sent request to join this camp', 403);
        }
        $request =  $user->requests()->create(
            [
                'type' => 'join_camp',
                'data' => [
                    'model' => Student::class,
                    'user_id'=>$user->id,
                    'camp_id' => $camp->id
                ]
            ]
        );

        Notification::send($camp->admins, new joinCampRequest($camp,'Student',auth()->user() ,$request->id));
        return $request;
    }

    function getAllJoinRequest(){
        $user = auth()->user();
        return $user->requests()->where('type', 'join_camp')
            ->get();
    }


}
