<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
      'id','description','title','type','path','created_at','updated_at'
    ];

    protected $hidden = ['path'];

    public function getUrlAttribute()
    {
        return url(Storage::url($this->path));
    }
    public function getVisiblePathAttribute()
    {
        return $this->attributes['path'];
    }
    public function documentable()
    {
        return $this->morphTo();
    }
}
