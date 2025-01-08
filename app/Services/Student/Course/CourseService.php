<?php

namespace App\Services\Student\Course;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;

class CourseService
{

    function getAll()
    {
        return auth()->user()->courses()->with('teacher')->get();
    }

    function join($course_id)
    {
        return auth()->user()->courses()->syncWithoutDetaching([$course_id]);
    }

    function leave($course_id)
    {
        return auth()->user()->courses()->detach([$course_id]);
    }

    function available()
    {
        return Course::whereDoesntHave('students', function ($query) {
            $query->where('student_id', auth()->user()->id);
        })->get();
    }

    function timeline()
    {
        $student = auth()->user();

        $data = Student::with([
            'courses'=>function($query){
                $studentAssignmentsId = auth()->user()->assignments()->pluck('related_to');
                $query->with([
                    'assignments'=>function($query)use($studentAssignmentsId){
                        $query->whereNotIn('id',$studentAssignmentsId);
                        $query->where('end_in', '>', now());
                    }
                ])->get();
                $query->with([
                    'quizzes'=>function($query){
                        $query->visibility();
                        $query->WhereDoesntHave('quizAttempts', function (Builder $query) {
                            $query->where('student_id', auth()->user()->id);
                        })->get();
                        $query->where('end_in', '>', now());

                    }
                ])->get();
            }
        ])->find($student->id);
        return $data;
//        $assignments = collect($data['courses'])
//            ->pluck('assignments')
//            ->flatten()
//            ->toArray();
//
//        $quizzes = collect($data['courses'])
//            ->pluck('quizzes')
//            ->flatten()
//            ->toArray();
//
//        return collect($assignments)->merge($quizzes)->sortBy('end_in')->toArray();

    }
}
