<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasFactory , HasApiTokens, SoftDeletes,Notifiable;

    protected $fillable = [
        'name', 'email', 'camp_id','camp_name', 'password', 'sex', 'age', 'level', 'created_at','updated_at', 'status','phone_number'
    ];

    // Optionally, add hidden attributes for security
    protected $hidden = [
        'password'
    ];

    public function camp()
    {
        return $this->belongsTo(Camp::class);
    }

    public function requests(){
        return $this->morphMany(Request::class,'requestable');
    }

    public function courses(){
        return $this->belongsToMany(Course::class);
    }

    public function assignments(){
        return $this->morphMany(Assignment::class,'assignmentable');
    }

    function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function communications()
    {
        return $this->morphMany(Communication::class, 'communicationable');
    }
}
