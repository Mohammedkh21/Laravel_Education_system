<?php

namespace App\Services\Admin\Student;

use App\Models\Admin;
use App\Models\Student;

class StudentService
{

    public function __construct(public Admin $admin)
    {
        $this->admin = auth()->user();
    }
    function getAll()
    {
        return  $this->admin->students();
    }

    function show(Student $student)
    {
        return Student::where('id',$student->id)->with([
            "courses",
            "assignments.grade",
            "quizAttempts.grade",
        ])->get();
    }

    function update($data,$student)
    {
        return $student->update($data);
    }

    function destroy($student){
        return $student->delete();
    }

    function search($name)
    {
        return  $this->admin->students($name);
    }

}
