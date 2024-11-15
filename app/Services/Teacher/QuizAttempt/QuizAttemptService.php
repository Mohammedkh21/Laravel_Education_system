<?php

namespace App\Services\Teacher\QuizAttempt;

use App\Models\Student;

class QuizAttemptService
{

    function getAll($course, $quiz)
    {
        $attempts = $quiz->quizAttempts()->with('grade')->get()->map(function ($attempt){
            $attempt->student = Student::findOrFail($attempt->student_id);
            unset($attempt->student_id);
            return $attempt;
        });
        $attempts->makeHidden(['data']);

        return $attempts;
    }

    function show($course, $quiz ,$quizAttempt)
    {

        $quizQuestions = $quiz->questions->keyBy('id');

        return $quiz->quizAttempts()->where('id',$quizAttempt->id)->get()->map(function ($attempt) use ($quizQuestions) {
            $attempt->data = collect($attempt->data)->map(function ($dataItem) use ($quizQuestions) {
                if (isset($dataItem['question_id']) && $quizQuestions->has($dataItem['question_id'])) {
                    $dataItem['question'] = $quizQuestions[$dataItem['question_id']];
                    unset($dataItem['question_id']);
                }
                return $dataItem;
            });
            return $attempt;
        })->first();
    }

    function updateDegree($course, $quiz ,$quizAttempt,$new_grade)
    {
        $grade = $quiz->quizAttempts()->find($quizAttempt->id)->grade->update([
            'result' => $new_grade
        ]);

        return $grade;
    }

}
