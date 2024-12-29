<?php

namespace App\Http\Controllers\Teacher\Course\Quiz\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionStoreRequest;
use App\Http\Requests\QuestionUpdateRequest;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\Teacher\Question\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class QuestionController extends Controller implements  HasMiddleware
{
    public function __construct(public QuestionService $questionService)
    {
    }

    public static function middleware(): array
    {
        return [
            'can:access,course',
            'can:access,quiz',
            new Middleware('can:access,question',except:['index','store'])

        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course,Quiz $quiz)
    {
        return response()->json(
            $this->questionService->getAll($quiz)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionStoreRequest $request,Course $course,Quiz $quiz)
    {
        return response()->json(
            $this->questionService->store($quiz,$request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course,Quiz $quiz,Question $question)
    {
        return response()->json(
            $this->questionService->show($quiz,$question)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionUpdateRequest $request, Course $course,Quiz $quiz,Question $question)
    {
        return response()->json(
            $this->questionService->update($question,$request->getData())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course,Quiz $quiz,Question $question)
    {
        return response()->json(
            $this->questionService->destroy($question)
        );
    }
}
