<?php

namespace App\Http\Controllers\Admin\Student;

use App\Http\Requests\StudentUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Services\Admin\Student\StudentService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StudentController implements HasMiddleware
{
    public function __construct(public  StudentService $studentService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:access,student', only:['update','show','destroy']),
        ];
    }
    function index()
    {
        return response()->json(
            $this->studentService->getAll()
        );
    }

    function show(Student $student)
    {
        return response()->json(
            $this->studentService->show( $student)
        );
    }

    function update(StudentUpdateRequest $request,Student $student)
    {
        return response()->json(
            $this->studentService->update($request->getData() , $student)
        );
    }

    function destroy(Student $student)
    {
        return response()->json(
            $this->studentService->destroy( $student)
        );
    }

    function search($name)
    {
        return response()->json(
            $this->studentService->search( $name)
        );
    }


    public function approveStudent(Request $request, $studentId)
        {
            $student = Student::findOrFail($studentId);
            $student->status = 'approved';
            $student->save();

            return response()->json([
                  'message' => 'Student has been approved successfully',
                  'student' => $student
             ], 200);
        }

    public function rejectStudent(Request $request, $studentId)
        {
            $student = Student::findOrFail($studentId);
            $student->status = 'rejected';
            $student->save();

            return response()->json([
                'message' => 'Student has been rejected',
                'student' => $student
            ], 200);

        }

}
