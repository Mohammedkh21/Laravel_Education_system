<?php

namespace App\Http\Controllers\Admin\Teacher;

use App\Http\Requests\TeacherUpdateRequest;
use App\Models\Camp;
use App\Models\Teacher;
use App\Services\Admin\Teacher\TeacherService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TeacherController implements  HasMiddleware
{

    public function __construct(public  TeacherService $teacherService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:access,teacher', only:['update','show','destroy']),
        ];
    }

    function index()
    {
        return response()->json(
            $this->teacherService->getAll()
        );
    }

    function show(Teacher $teacher)
    {
        return response()->json(
            $this->teacherService->show( $teacher)
        );
    }

    function update(TeacherUpdateRequest $request,Teacher $teacher)
    {
        return response()->json(
            $this->teacherService->update($request->getData() , $teacher)
        );
    }

    function destroy(Teacher $teacher)
    {
        return response()->json(
            $this->teacherService->destroy( $teacher)
        );
    }

    function search($name)
    {
        return response()->json(
            $this->teacherService->search( $name)
        );
    }
}
