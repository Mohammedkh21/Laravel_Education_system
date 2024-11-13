<?php

namespace App\Services\Teacher\Comunication;

use App\Models\Communication;
use App\Models\Teacher;

class CommunicationService
{

    function getAll($teacher_id)
    {
        return Teacher::find($teacher_id)->communications;
    }

    function store($teacher_id,$data)
    {
        return Teacher::find($teacher_id)->communications()->create($data);
    }

    function update($teacher_id,$data,$communication)
    {
        return Teacher::find($teacher_id)->communications()->find($communication->id)->update($data);
    }

    function delete($teacher_id,$communication)
    {
        return Teacher::find($teacher_id)->communications()->find($communication->id)->delete();
    }

}
