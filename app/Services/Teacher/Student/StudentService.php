<?php

namespace App\Services\Teacher\Student;

class StudentService
{
    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    function getAll()
    {
        return $this->user->students;
    }

    function contacts($student)
    {
        return $this->user->students()->with('communications')->find($student);
    }

    function courseStudent($course)
    {
        return $course->students;
    }

    function studentEstimates($course,$student)
    {
        return $student->load([
            'courses'=>function($query) use ($course) {
                $query->where('courses.id', $course->id);
            },'assignments'=>function($query) use ($course) {
                $query->whereIn('related_to', $course->assignments->pluck('id') );
            }
            ,'assignments.grade'
            ,'quizAttempts'=>function($query) use ($course) {
                $query->whereHas('quiz', function ($quizQuery) use ($course) {
                    $quizQuery->where('course_id', $course->id); // Filter quiz attempts for the specific course
                });
            }
            ,'quizAttempts.grade'
        ]);
    }
}
