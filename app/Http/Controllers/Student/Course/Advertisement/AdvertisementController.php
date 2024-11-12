<?php

namespace App\Http\Controllers\Student\Course\Advertisement;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function __invoke(Course $course)
    {
        return response()->json(
            $course->advertisements
        );
    }
}
