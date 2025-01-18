<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\Student\Estimates\EstimatesService;
use Illuminate\Http\Request;

class EstimatesController extends Controller
{
    //
    public function __construct(public EstimatesService $estimatesService)
    {
    }

    public function __invoke(Course $course)
    {
        return response()->json(
            $this->estimatesService->getCourseEstimates($course)
        );
    }
}
