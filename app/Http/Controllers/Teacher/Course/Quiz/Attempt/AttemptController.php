<?php

namespace App\Http\Controllers\Teacher\Course\Quiz\Attempt;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\Teacher\QuizAttempt\QuizAttemptService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class AttemptController extends Controller implements  HasMiddleware
{

    public function __construct(public QuizAttemptService $quizAttemptService)
    {
    }
    public static function middleware(): array
    {
        return [
            'can:access,course',
            'can:access,quiz',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Course $course,Quiz $quiz)
    {
        return response()->json(
            $this->quizAttemptService->getAll($course, $quiz)
        );
    }


    /**
     * Display the specified resource.
     */
    public function show(Course $course, Quiz $quiz, QuizAttempt $quizAttempt)
    {
        return response()->json(
            $this->quizAttemptService->show($course,  $quiz,  $quizAttempt)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Quiz $quiz, QuizAttempt $quizAttempt)
    {
        $request->validate([
            'grade' => 'required|numeric|max:' . $quiz->degree,
        ]);
        return response()->json(
            $this->quizAttemptService->updateDegree($course,  $quiz,  $quizAttempt,$request->grade)
        );
    }


}
