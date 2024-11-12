<?php

namespace App\Policies;

use App\Models\Advertisement;
use App\Models\Lecture;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdvertisementPolicy
{
    function access(Teacher $teacher,Advertisement $advertisement)
    {
        return
            $teacher->courses()->with('advertisements')->get()
                ->pluck('advertisements')->flatten()->where('id', $advertisement->id)->isNotEmpty()
            ? Response::allow()
            : Response::deny('You do not have permission to access on this advertisement');
    }

}
