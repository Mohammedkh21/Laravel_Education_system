<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\Teacher\Student\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function __construct(public StudentService $studentService)
    {
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
}
