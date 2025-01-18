<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Services\Student\Teacher\TeacherService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    //
    public function __construct(public TeacherService $teacherService)
    {
    }

    public function index(){
        return response()->json(
            $this->teacherService->getAll()
        );
    }

    public function show(Teacher $teacher){
        return response()->json(
            $this->teacherService->show($teacher)
        );
    }
}
