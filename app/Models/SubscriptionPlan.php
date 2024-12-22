<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
      'id','name','teachers','students','created_at','updated_at'
    ];

    function admins()
    {
        return $this->hasMany(Admin::class);
    }
}
