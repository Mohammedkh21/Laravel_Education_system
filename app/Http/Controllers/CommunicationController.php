<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComunicationStoreRequest;
use App\Http\Requests\ComunicationUpdateRequest;
use App\Models\Communication;
use App\Services\Communication\CommunicationService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CommunicationController extends Controller   implements HasMiddleware
{
    //
    protected CommunicationService $communicationService;

    public static function middleware(): array
    {
        return [
            new Middleware('can:access,communication',only:['show','update','destroy'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            $this->communicationService->getAll()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ComunicationStoreRequest $request)
    {
        return response()->json(
            $this->communicationService->store($request->getData())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Communication $communication)
    {
        return response()->json(
            $this->communicationService->show($communication)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ComunicationUpdateRequest $request, Communication $communication)
    {
        return response()->json(
            $this->communicationService->update($request->getData(),$communication)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Communication $communication)
    {
        return response()->json(
            $this->communicationService->delete($communication)
        );
    }
}
