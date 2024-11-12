<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',	'text',	'color',	'course_id',	'created_at',	'updated_at'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
