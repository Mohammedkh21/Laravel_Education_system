<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',	'title',	'description',	'course_id','time',	'degree',	'visibility','result_visible',	'start_in',	'end_in'	,'created_at'	,'updated_at'
    ];

    public function scopeVisibility(Builder $query): void
    {
        $query->where('visibility', true);
    }

    public function scopeResultVisible(Builder $query): void
    {
        $query->where('result_visible', true);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }

    function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
