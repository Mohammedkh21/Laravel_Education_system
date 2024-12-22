<?php

namespace App\Services\Admin;


use App\Models\Admin;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Hash;
use App\Services\Authentication;

class AuthService extends Authentication\AuthService
{

    function register($class,$data){
        $freePlan = SubscriptionPlan::where('name', 'free')->first();
        if (!$freePlan) {
            throw new \Exception('Subscription plan "free" not found.');
        }

        $data['password'] = Hash::make($data['password']);
        $admin = $class::create($data);
        $admin->subscriptionPlan()->associate($freePlan);
        $admin->save();

        return $admin;
    }

}
