<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComunicationStoreRequest;
use App\Http\Requests\ComunicationUpdateRequest;
use App\Models\Communication;
use App\Services\Teacher\Comunication\CommunicationService;
use Illuminate\Http\Request;

class ComunicationController extends Controller
{
    public function __construct(public CommunicationService $communicationService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            $this->communicationService->getAll(auth()->user()->id)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ComunicationStoreRequest $request)
    {
        return response()->json(
            $this->communicationService->store(auth()->user()->id,$request->getData())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Communication $communication)
    {
        return response()->json(
            $communication
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ComunicationUpdateRequest $request, Communication $communication)
    {
        return response()->json(
            $this->communicationService->update(auth()->user()->id,$request->getData(),$communication)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Communication $communication)
    {
        return response()->json(
            $this->communicationService->delete(auth()->user()->id,$communication)
        );
    }
}
