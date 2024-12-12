<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\Admin\Course\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(public CourseService $courseService)
    {
    }

    function show(Course $course)
    {
        return response()->json(
            $this->courseService->show($course)
        );
    }
}
