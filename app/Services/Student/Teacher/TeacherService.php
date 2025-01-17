<?php

namespace App\Services\Student\Teacher;

class TeacherService
{

    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    function getAll()
    {
        return $this->user->teachers();
    }

    function contacts($teachers)
    {
        return $this->user->teachers($teachers->id);
    }
}
