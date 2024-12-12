<?php

namespace App\Services\Admin\Teacher;

use App\Models\Admin;
use App\Models\Teacher;

class TeacherService
{

    public function __construct(public Admin $admin)
    {
        $this->admin = auth()->user();
    }
    function getAll()
    {
        return $this->admin->teachers();
    }

    function show(Teacher $teacher)
    {
        $dbTeacher = Teacher::where('id',$teacher->id)->with([
            "communications",
            "courses.students"
        ])->first();

        $students = $dbTeacher->courses
            ->flatMap(function ($course) {
                return $course->students;
            })
            ->unique('id');
        $courses = $dbTeacher->courses->map(function ($course) {
            return collect($course)->except('students');
        });
        return [
            'teacher' => $teacher,
            'courses' => $courses,
            'communications' => $dbTeacher->communications,
            'students' => $students,
        ];
    }


    function update($data,$teacher)
    {
        return $teacher->update($data);
    }

    function destroy($teacher){
        return $teacher->delete();
    }

    function search($name)
    {
        return  $this->admin->teachers($name);
    }

}
