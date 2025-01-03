<?php

namespace App\Http\Controllers\Teacher\Course\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentStoreRequest;
use App\Models\Course;
use App\Models\Document;
use App\Services\Teacher\Document\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DocumentController extends Controller  implements HasMiddleware
{

    public function __construct(public DocumentService $documentService)
    {

    }

    public static function middleware(): array
    {
        return [
            'can:access,course',
            new Middleware('can:access,document',only:['show','destroy'])

        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        return response()->json(
            $this->documentService->getAll($course)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocumentStoreRequest $request,Course $course)
    {
        return response()->json(
            $this->documentService->store($course,$request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course,Document $document)
    {
        return $this->documentService->download($document);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course,Document $document)
    {
        return response()->json(
            $this->documentService->destroy( $document)
        );
    }
}
