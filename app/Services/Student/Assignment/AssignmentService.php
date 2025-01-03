<?php

namespace App\Services\Student\Assignment;

use App\Models\Assignment;
use App\Models\Lecture;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentService
{

    function getAll($course)
    {
        return $course->assignments()->visibility()->get();
    }

    function show($course,$assignment)
    {
        return $course->assignments()->visibility()->with('documents')->find($assignment->id)
            ??
            throw new \Exception('you dont have access on this assignment',403);
    }

    function showSubmit($course,$assignment)
    {
        $this->show($course,$assignment);

        $student = auth()->user();

        return $student->assignments()
            ->where('type', 'submit')
            ->where('related_to', $assignment->id)
            ->with('documents')
            ->with('grade')
            ->first();
    }

    function deleteSubmit($course,$assignment)
    {
        $this->show($course,$assignment);

        $student = auth()->user();

        $submit = $student->assignments()
            ->where('type', 'submit')
            ->where('related_to', $assignment->id)
            ->with('grade')
            ->first();
        if ($submit->grade || !Carbon::now()->lt($assignment->end_in)){
            throw new \Exception('you cant delete the submit',403);
        }
        if ($submit){
            return $submit->delete();
        }
        throw new \Exception('you dont have submit to delete ',403);
    }

    function submit($request,$course,$course_assignment)
    {
        $submit=$this->showSubmit($course,$course_assignment);
        if (!Carbon::now()->lt($course_assignment->end_in)){
            throw new \Exception('you cant submit : timeout',403);
        }
        if ($submit){
            throw new \Exception('you already added submit to this assignment',401);
        }

        $documents = [];
        $student = auth()->user();
        DB::beginTransaction();
        $assignment = $student->assignments()->create([
            'related_to'=>$course_assignment->id,
            'type'=>'submit'
        ]);
        try{
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();

                $path = $file->storeAs('uploads', $fileName, 'public');

                $documents[] =  $assignment->documents()->create([
                    'path'=> $path,
                    'type' => $file->getClientMimeType(),
                    'title'=> $request->input('title') ?? $file->getClientOriginalName(),
                    'description'=> $request->input('description')
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
        return  Assignment::with('documents')->find($assignment->id);
    }


}
