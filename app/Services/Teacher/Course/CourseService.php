<?php

namespace App\Services\Teacher\Course;

use App\Models\Teacher;

class CourseService
{

    function getAll()
    {
        return auth()->user()->courses;
    }

    function store($data)
    {
        return auth()->user()->courses()->create($data);
    }

    function update($course , $data ){
        return $course->update($data);
    }

    function destroy($course ){
        return $course->delete();
    }

    function timeLine(){
        return auth()->user()->courses()->with(['assignments','quizzes'])->get();
    }
}
