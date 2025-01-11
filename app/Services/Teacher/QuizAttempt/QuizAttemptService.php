<?php

namespace App\Services\Teacher\QuizAttempt;

use App\Models\QuizAttempt;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

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
        $quizQuestions = $quizAttempt->quiz->questions->keyBy('id');

        $attemptData = collect(json_decode($quizAttempt->data, true) ?? []);

        $updatedData = $quizQuestions->map(function ($question) use ($attemptData) {
            $studentAnswer = $attemptData->firstWhere('question_id', $question->id);

            $documents= $question->documents->each(function ($document) {
                $document->url = url(Storage::url($document->path));
            });
            return [
                'question_id' => $question->id,
                'title' => $question->title,
                'type' => $question->type,
                'options' => $question->options,
                'correct_answer' => $question->correct_answer,
                'mark' => $question->mark,
                'student_answer' => $studentAnswer['answer'] ?? '',
                'is_correct' => isset($studentAnswer['answer']) && $studentAnswer['answer'] === $question->correct_answer,
                'documents' => $documents,
            ];
        });

        $quizAttempt->data = $updatedData->values();
//        $quizAttempt->makeHidden(["quiz"]);

        return $quizAttempt;
    }

    function updateDegree($course, $quiz ,$quizAttempt,$new_grade)
    {
        $grade = $quiz->quizAttempts()->find($quizAttempt->id)->grade->update([
            'result' => $new_grade
        ]);

        return $grade;
    }

}
