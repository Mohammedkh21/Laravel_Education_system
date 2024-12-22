<?php

namespace App\Http\Controllers\Admin\Camp;

use App\Http\Controllers\Controller;
use App\Http\Requests\CampStoreRequest;
use App\Http\Requests\CampUpdateRequest;
use App\Models\Camp;
use App\Services\Admin\Camp\CampService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CampController extends Controller implements HasMiddleware
{

    public function __construct(public CampService $campService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:access,camp', only:['update','show','destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            $this->campService->index()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CampStoreRequest $request)
    {
        return response()->json(
            $this->campService->store($request->getData())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Camp $camp)
    {
        return response()->json(
            $camp
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CampUpdateRequest $request, Camp $camp)
    {
        return response()->json(
            $this->campService->update($camp,$request->getData())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Camp $camp)
    {
        return response()->json(
            $this->campService->destroy($camp)
        );
    }
}
