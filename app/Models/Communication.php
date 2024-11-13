<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    use HasFactory;

    protected $fillable = [
      'id','name','type','content','created_at','updated_at'
    ];

    function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
