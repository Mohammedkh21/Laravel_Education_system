<?php

namespace App\Services\Admin\Course;

use App\Models\Course;

class CourseService
{

    function show($course)
    {
        return Course::where('id',$course->id)->with([
            "lectures",
            "assignments",
            "quizzes",
            "advertisements",
        ])->get();
    }
}
