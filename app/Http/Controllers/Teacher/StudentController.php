<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Services\Teacher\Student\StudentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StudentController extends Controller implements HasMiddleware
{

    public function __construct(public StudentService $studentService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:access,student', only:['contacts','studentEstimates']),
            new Middleware('can:access,course', only:['courseStudent','studentEstimates']),
        ];
    }

    public function index(){
        return response()->json(
            $this->studentService->getAll()
        );
    }

    public function contacts(Student $student){
        return response()->json(
            $this->studentService->contacts($student)
        );
    }

    public function courseStudent(Course $course){
        return response()->json(
            $this->studentService->courseStudent($course)
        );
    }

    public function studentEstimates(Course $course,Student $student)
    {
        return response()->json(
            $this->studentService->studentEstimates($course,$student)
        );
    }


}
