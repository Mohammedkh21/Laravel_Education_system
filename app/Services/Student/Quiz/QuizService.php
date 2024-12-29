<?php

namespace App\Services\Student\Quiz;

use Illuminate\Support\Facades\Storage;

class QuizService
{

    function getAll($course)
    {
        return $course->quizzes()->visibility()->get();
    }

    function show($course,$quiz)
    {
        return
            $course->quizzes()->visibility()->with(['quizAttempts'=>function($query) use ($quiz){
                $query->where('student_id', auth()->user()->id);
                if ($quiz->result_visible) {
                    $query->with('grade');
                }
            }])
                ->find($quiz->id)
            ??
            throw new \Exception('you dont have access on this quiz',403);
    }

    function attempt($course,$quiz)
    {
        $this->show($course,$quiz);

        $quizAttempt = auth()->user()->quizAttempts()->firstOrCreate([
            'quiz_id'=> $quiz->id,
        ]);
        $expiresAt = $quizAttempt->created_at->addMinutes($quizAttempt->quiz->time);

        if (now()->lessThan($expiresAt) && now()->lessThan($quizAttempt->quiz->end_in)  ) {
            return $quizAttempt;
        }

        throw new \Exception('attempt finished',403);
    }

    function attemptQuestions($course,$quiz)
    {
        $quizAttempt = $this->attempt($course,$quiz);
        return $quiz->questions()->with('documents')->get()->each(function ($question) {
            $question->documents->each(function ($document) {
                $document->url = url(Storage::url($document->path));
            });
        })->makeHidden(['correct_answer']);
    }

    function submitAttempt($questions,$course,$quiz)
    {
        $quizAttempt = $this->attempt($course,$quiz);
        $existingData = json_decode($quizAttempt->data, true) ?? [];

        foreach ($questions as $question) {
            $newQuestion = json_decode($question, true);

            $questionExists = false;
            foreach ($existingData as &$existingQuestion) {
                if ($existingQuestion['question_id'] == $newQuestion['question_id']) {
                    $existingQuestion['answer'] = $newQuestion['answer'];
                    $questionExists = true;
                    break;
                }
            }
            if (!$questionExists) {
                $existingData[] = $newQuestion;
            }
        }

        $quizAttempt->data = json_encode($existingData);
        $quizAttempt->save();

        $quizQuestions = $quiz->questions()->get();
        $grade = 0;

        foreach ($quizQuestions as $quizQuestion) {
            foreach ($existingData as $submittedQuestion) {
                if ($submittedQuestion['question_id'] == $quizQuestion->id) {
                    if ($submittedQuestion['answer'] == $quizQuestion->correct_answer) {
                        $grade += $quizQuestion->mark;
                    }
                }
            }
        }

        $quisGrade = $quizAttempt->grade()->first();
        if ($quisGrade){
            $quisGrade->update(['result'=>$grade]);
        }else{
            $quizAttempt->grade()->create(['result'=>$grade]);
        }

        return true;
    }


}
