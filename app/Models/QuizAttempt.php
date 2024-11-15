<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','student_id','quiz_id','data','created_at','updated_at'
    ];

    protected $casts = [
      'data'  => 'json'
    ];

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function grade(){
        return $this->morphOne(Grade::class,'gradeable');
    }
}
