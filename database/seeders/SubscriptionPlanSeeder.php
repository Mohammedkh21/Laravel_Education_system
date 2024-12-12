<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubscriptionPlan::create([
            'name'=>'free',
            'teachers'=>'3',
            'students'=>'30',
        ]);
        SubscriptionPlan::create([
            'name'=>'paid A',
            'teachers'=>'5',
            'students'=>'50',
            "price"=>19.99
        ]);
        SubscriptionPlan::create([
            'name'=>'paid B',
            'teachers'=>'70',
            'students'=>'700',
            "price"=>29.99
        ]);
        SubscriptionPlan::create([
            'name'=>'unlimited',
            'teachers'=>'0',
            'students'=>'0',
            "price"=>39.99
        ]);
    }
}
