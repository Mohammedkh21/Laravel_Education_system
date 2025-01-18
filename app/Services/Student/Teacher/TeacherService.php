<?php

namespace App\Services\Student\Teacher;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeacherService
{

    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    function getAll()
    {
        return $this->user->courses()->with(['teacher'])->get()->pluck('teacher');
    }

    function show($teacher)
    {
        return $this->user->courses()->with(['teacher' => function($query) use ($teacher) {
            $query->where('id', $teacher->id);
        },'teacher.communications','teacher.courses'])->get()->pluck('teacher')->filter()->first() ?? throw new \Exception('you dont related to this teacher ');
    }
}
