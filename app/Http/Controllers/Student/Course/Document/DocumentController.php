<?php

namespace App\Http\Controllers\Student\Course\Document;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Document;
use App\Services\Student\Document\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DocumentController extends Controller  implements HasMiddleware
{

    public function __construct(public  DocumentService $documentService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:access,document',only:['show'])
        ];
    }

    public function index(Course $course)
    {
        return response()->json(
            $this->documentService->getAll($course)
        );
    }

    public function show(Course $course,Document $document)
    {
        return $this->documentService->download($document);
    }
}
