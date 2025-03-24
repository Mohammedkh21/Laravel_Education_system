<?php

namespace App\Services\Admin;


use App\Models\Admin;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Hash;
use App\Services\Authentication;

class AuthService extends Authentication\AuthService
{

    public function register($class,$data, $camp_id = null){
        $freePlan = SubscriptionPlan::where('name', 'free')->firstOrFail();
        $data['password'] = Hash::make($data['password']);
        $admin = $class::create($data);
        $admin->subscriptionPlan()->associate($freePlan);
        $admin->save();

        return $admin;
    }

}
