<?php

namespace App\Services\Teacher\Lecture;

use App\Models\Lecture;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LectureService
{

    function getAll($course)
    {
        return $course->lectures()->with('documents')->get();
    }

    function show($course,$lecture)
    {
        return $course->lectures()->with('documents')->find($lecture->id);
    }

    function store($course,$request)
    {

        $documents = [];
        DB::beginTransaction();
        $lecture = $course->lectures()->create($request->getData());
        if ( !$request->has('files') ){
            DB::commit();
            return $lecture;
        }
        try{
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();

                $path = $file->storeAs('uploads', $fileName, 'public');

                $documents[] =  $lecture->documents()->create([
                    'path'=> $path,
                    'type' => $file->getClientMimeType(),
                ]);
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
        return  Lecture::with('documents')->find($lecture->id);
    }


    function update($lecture,$data)
    {
        return $lecture->update($data);
    }

    function destroy($lecture)
    {
        return $lecture->delete();
    }

}
