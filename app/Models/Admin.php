<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory , HasApiTokens , SoftDeletes,Notifiable;

    protected $fillable = [
        'name', 'email',  'role','password','sex', 'phone_number', 'age','created_at','updated_at'
    ];

    protected $hidden = [
        'password', 'id'
    ];

    public function camps()
    {
        return $this->morphToMany(Camp::class, 'campable')->withTimestamps();
    }

    public function students()
    {
        return Student::whereIn('camp_id', $this->camps()->pluck('camps.id'))->get();
    }

    public function teachers()
    {
        $camps_id = $this->camps()->pluck('camps.id');
        return Teacher::whereHas('camps', function ($query) use ($camps_id) {
                        $query->whereIn('camps.id', $camps_id);
                    })->get();
    }

    function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

}
