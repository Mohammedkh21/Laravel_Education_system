<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    function access($user,Student $student)
    {


        if ($user instanceof Admin) {
            return $this->adminAccess($user, $student);
        }

        if ($user instanceof Teacher) {
            return $this->teacherAccess($user, $student);
        }
        return false;
    }

    private function teacherAccess(Teacher $teacher, Student $student)
    {
        return $teacher->students()->where('students.id',$student->id)->get()->isNotEmpty()
            ? Response::allow()
            : Response::deny('You do not have permission to access on this student');
    }

    private function adminAccess(Admin $admin, Student $student)
    {
        return $admin->students()->contains($student->id)
            ? Response::allow()
            : Response::deny('You do not have permission to access on this student');
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
    public function view(User $user, Student $student): bool
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
    public function update(User $user, Student $student): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Student $student): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        //
    }
}
