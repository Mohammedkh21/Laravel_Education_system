<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'id','teacher_id','description','title','created_at','updated_at','type'
    ];

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function students(){
        return $this->belongsToMany(Student::class);
    }

    public function lectures(){
        return $this->hasMany(Lecture::class);
    }

    public function assignments(){
        return $this->morphMany(Assignment::class,'assignmentable');
    }

    public function quizzes(){
        return $this->hasMany(Quiz::class);
    }
}
