<?php

namespace App\Http\Controllers\Student\Course\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Services\Student\Quiz\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(public QuizService $quizService)
    {
    }

    function index(Course $course)
    {
        return response()->json(
            $this->quizService->getAll($course)
        );
    }

    function show(Course $course,Quiz $quiz)
    {
        return response()->json(
            $this->quizService->show($course,$quiz)
        );
    }

    function attempt(Course $course,Quiz $quiz)
    {
        return response()->json(
            $this->quizService->attemptQuestions($course,$quiz)
        );
    }

    function submitAttempt(Request $request,Course $course,Quiz $quiz)
    {
        $request->validate([
            'questions'=>'required'
        ]);
        return response()->json(
            $this->quizService->submitAttempt($request->input('questions'),$course,$quiz)
        );
    }

    function reviewAttempt(Course $course,Quiz $quiz)
    {
        return response()->json(
            $this->quizService->reviewAttempt($course,$quiz)
        );
    }
}
