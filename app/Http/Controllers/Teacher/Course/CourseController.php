<?php

namespace App\Http\Controllers\Teacher\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Models\Course;
use App\Services\Teacher\Course\CourseService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class CourseController extends Controller implements HasMiddleware
{
    public function __construct(public CourseService $courseService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:access,course', only:['update','show','destroy']),
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            $this->courseService->getAll()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseStoreRequest $request)
    {
        return response()->json(
            $this->courseService->store($request->getData())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return response()->json($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseUpdateRequest $request, Course $course)
    {
        return response()->json(
            $this->courseService->update($course,$request->getData())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        return response()->json(
            $this->courseService->destroy($course)
        );
    }

    public function timeLine(){
        return response()->json(
            $this->courseService->timeLine()
        );
    }
}
