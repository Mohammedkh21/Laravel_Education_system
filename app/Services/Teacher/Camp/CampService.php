<?php

namespace App\Services\Teacher\Camp;

use App\Models\Teacher;
use App\Notifications\joinCampRequest;
use App\Services\Teacher\Request\RequestService;
use Illuminate\Support\Facades\Notification;

class CampService
{
    public function __construct(public Teacher $teacher)
    {
        $this->teacher = auth()->user();
    }

    function showAll()
    {
        return $this->teacher->camps ;
    }



    function joinCamp($camp)
    {
        $user = auth()->user();
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
                    'model' => Teacher::class,
                    'user_id'=>$user->id,
                    'camp_id' => $camp->id
                ]
            ]
        );

        Notification::send($camp->admins, new joinCampRequest($camp,'Teacher',auth()->user() ,$request->id));
        return $request;
    }

    function forget($camp_id)
    {
        return $this->teacher->camps()->detach($camp_id);
    }
}
