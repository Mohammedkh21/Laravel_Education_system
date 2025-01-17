<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use HasFactory , HasApiTokens , SoftDeletes ,Notifiable;

    protected $fillable = [
        'name', 'email','password',  'specialization', 'sex', 'phone_number', 'age','created_at','updated_at'
    ];

    protected $hidden = [
        'password'
    ];

    public function camps()
    {
        return $this->morphToMany(Camp::class, 'campable')->withTimestamps();
    }

    public function requests(){
        return $this->morphMany(Request::class,'requestable');
    }

    public function courses(){
        return $this->hasMany(Course::class);
    }

    function students()
    {
        return $this->hasManyThrough(
          Student::class,
          Course::class,
            'id',           // Foreign key on courses table
            'id',           // Foreign key on students table
            'id',           // Local key on teachers table
            'id'
        );
    }
    public function communications()
    {
        return $this->morphMany(Communication::class, 'communicationable');
    }
}
