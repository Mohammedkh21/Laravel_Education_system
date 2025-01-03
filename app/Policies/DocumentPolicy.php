<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Document;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
{
    function access($user,Document $document)
    {
        if ($user instanceof Student) {
            return $this->studentAccess($user, $document);
        }

        if ($user instanceof Teacher) {
            return $this->teacherAccess($user, $document);
        }

        return false;
    }
    private function studentAccess(Student $student, Document $document)
    {
        return
            $student->courses()->with(['lectures.documents','assignments'=>function($query){
                $query->visibility()->with('documents');
            }])
                ->get()
                ->flatMap(function ($course) use ($student) {
                    return $course->lectures->flatMap(function ($lecture) {
                        return $lecture->documents;
                    })->merge($course->assignments->flatMap(function ($assignment) {
                        return $assignment->documents;
                    }))->merge($student->assignments->flatMap(function ($assignment) {
                        return $assignment->documents;
                    }))->merge($course->documents);
                })
                ->where('id', $document->id)
                ->isNotEmpty()
            ? Response::allow()
            : Response::deny('You do not have permission to access on this document');

    }
    private function teacherAccess(Teacher $teacher, Document $document)
    {

        return
            $teacher->courses()->with(['lectures.documents','assignments.documents'])->get()
                ->flatMap(function ($course) {
                    return $course->lectures->flatMap(function ($lecture) {
                        return $lecture->documents;
                    })->merge($course->assignments->flatMap(function ($assignment) {
                        return $assignment->documents;
                    }))->merge($course->documents);
                })
                ->where('id', $document->id)
                ->isNotEmpty()
            ? Response::allow()
            : Response::deny('You do not have permission to access on this document');
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Document $document): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        //
    }
}
