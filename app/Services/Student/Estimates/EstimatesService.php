<?php

namespace App\Services\Student\Estimates;

use App\Models\Student;

class EstimatesService
{
    private $student;
    public function __construct()
    {
        $this->student = auth()->user();
    }

    function getCourseEstimates($course)
    {

        return $this->student->load([
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
