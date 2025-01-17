<?php

namespace App\Services\Teacher\Student;

class StudentService
{
    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    function getAll()
    {
        return $this->user->students;
    }

    function contacts($student)
    {
        return $this->user->students()->with('communications')->find($student);
    }
}
