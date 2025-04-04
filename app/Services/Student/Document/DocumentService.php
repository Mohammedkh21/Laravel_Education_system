<?php

namespace App\Services\Student\Document;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentService
{

    function getAll($field)
    {
        return $field->documents()->get()->each(function ($document) {
            $document->url = url(Storage::url($document->path));
        });
    }

    function download($document)
    {
        if (Storage::disk('public')->exists($document->path)) {
            return Storage::disk('public')->download($document->path);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

}
