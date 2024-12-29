<?php

namespace App\Services\Teacher\Question;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionService
{

    function getAll($quiz)
    {
        $questions = $quiz->questions()->with('documents')->get()
            ->each(function ($question) {
                $question->documents->each(function ($document) {
                    $document->makeVisible(['path']);
                    $document->path = url(Storage::url($document->path));
                });
            });
        return $questions;
    }

    function show($quiz,$question)
    {

        $question = $quiz->questions()->with('documents')->find($question->id);
        $question->documents->each(function ($document) {
                $document->makeVisible(['path']);
                $document->path = url(Storage::url($document->path));
            });
        return $question;
    }

    function store($quiz,$request)
    {
        $documents = [];
        DB::beginTransaction();info(1);
        $question = $quiz->questions()->create($request->getData());
        if ( !$request->has('files') ){
//            DB::commit();
            return $question;
        }info(2);
        try{
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();

                $path = $file->storeAs('uploads', $fileName, 'public');

                $documents[] =  $question->documents()->create([
                    'path'=> $path,
                    'type' => $file->getClientMimeType(),
                ])->makeVisible('path');
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            foreach ($documents as $document) {
                if (Storage::disk('public')->exists($document->path)) {
                    Storage::disk('public')->delete($document->path);
                }
            }
            return  $e;
        }
        collect($documents)->each(function ($document){
            $document->path = url(Storage::url($document->path));
        });
        return [
            'question' => $question,
            'documents' => $documents
        ];
    }

    function update($question,$data)
    {
        return $question->update($data);
    }

    function destroy($question)
    {
        return $question->delete();
    }
}
