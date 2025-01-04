<?php

namespace App\Services\Teacher\Assignment;

use App\Models\Assignment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentService
{

    function getAll($course)
    {
        return $course->assignments()->with('documents')->get()
            ->each(function ($assignment) {
                $assignment->documents->each(function ($document) {
                    $document->url = url(Storage::url($document->path));
                });
            });
    }

    function show($course,$assignment)
    {
        $assignment = $course->assignments()->with('documents')->find($assignment->id);
        $assignment->documents->each(function ($document) {
            $document->url = url(Storage::url($document->path));
        });
        return $assignment;
    }

    function store($course,$request)
    {

        $documents = [];
        DB::beginTransaction();
        $assignment = $course->assignments()->create($request->getData());
        if ( !$request->has('files') ){
            DB::commit();
            return $assignment;
        }
        try{
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();

                $path = $file->storeAs('uploads', $fileName, 'public');

                $documents[] =  $assignment->documents()->create([
                    'path'=> $path,
                    'type' => $file->getClientMimeType(),
                    'title' => $request->input('title') ?? $file->getClientOriginalName()
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
        collect($documents)->each(function ($document){
            $document->url = url(Storage::url($document->path));
        });
//        return  Assignment::with('documents')->find($assignment->id);
        return [
            'assignment' => $assignment,
            'documents' => $documents
        ];
    }

    function update($assignment,$data)
    {
        return $assignment->update($data);
    }

    function destroy($assignment)
    {
        return $assignment->delete();
    }

    function studentSubmits($course,$assignment)
    {
        return Student::whereHas('courses', function($query) use ($course) {
            $query->where('courses.id', $course->id);
            })->with(['assignments' => function($query) use ($assignment) {
                    $query->where('assignments.type', 'submit')
                    ->where('assignments.related_to', $assignment->id);
            },'assignments.documents','assignments.grade'])
            ->get()
            ->each(function ($student) {
                $student->assignments->each(function ($assignment) {
                    $assignment->documents->each(function ($document) {
                        $document->url = url(Storage::url($document->path));
                    });
                });
            });
    }

    function downloadStudentSubmit($course,$assignment,$student)
    {
        $document = $this->studentSubmits($course,$assignment)
            ->where('id',$student->id)
            ->pluck('assignments.*.documents')
            ->flatten()
            ->first();
        if ($document){
            if (Storage::disk('public')->exists($document->path)) {
                return Storage::disk('public')->download($document->path);
            }
        }


        return throw new \Exception('no submit for this student');
    }

    function setRate($course,$assignment,$student,$mark)
    {
        $student_submit = Student::whereHas('courses', function($query) use ($course) {
                                $query->where('courses.id', $course->id);
                            })->where('id',$student->id)->first()
                            ->assignments()->where('assignments.type', 'submit')
                            ->where('assignments.related_to', $assignment->id)->first();
        if($mark > $assignment->degree){
            throw new \Exception('the maximum mark for this assignment is '.$assignment->degree,401);
        }
        if ($student_submit->grade) {
            $student_submit->grade()->update([
                'result' => $mark,
            ]);
        } else {
            $student_submit->grade()->create([
                'result' => $mark,
            ]);
        }

        return true;
    }

}
