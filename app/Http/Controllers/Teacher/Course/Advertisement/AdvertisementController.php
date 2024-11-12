<?php

namespace App\Http\Controllers\Teacher\Course\Advertisement;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisementStoreRequest;
use App\Http\Requests\AdvertisementUpdateRequest;
use App\Models\Advertisement;
use App\Models\Course;
use App\Services\Teacher\Advertisement\AdvertisemnetService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdvertisementController extends Controller implements HasMiddleware
{
    public function __construct(public AdvertisemnetService $advertisemnetService)
    {
    }

    public static function middleware(): array
    {
        return [
            'can:access,course',
            new Middleware('can:access,advertisement',except:['index','store'])

        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        return response()->json(
            $this->advertisemnetService->getAll($course)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdvertisementStoreRequest $request,Course $course)
    {
        return response()->json(
            $this->advertisemnetService->store($course,$request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course,Advertisement $advertisement)
    {
        return response()->json(
            $this->advertisemnetService->show($course,$advertisement)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdvertisementUpdateRequest $request, Course $course,Advertisement $advertisement)
    {
        return response()->json(
            $this->advertisemnetService->update($advertisement,$request->getData())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course,Advertisement $advertisement)
    {
        return response()->json(
            $this->advertisemnetService->destroy($advertisement)
        );
    }
}
