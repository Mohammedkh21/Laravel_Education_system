<?php

namespace App\Services\Teacher\Advertisement;

class AdvertisemnetService
{

    function getAll($course)
    {
        return $course->advertisements;
    }

    function show($course,$advertisement)
    {
        return $course->advertisements()->find($advertisement->id);
    }

    function store($course,$request)
    {
        return $course->advertisements()->create($request->getData());
    }

    function update($advertisement,$data)
    {
        return $advertisement->update($data);
    }

    function destroy($advertisement)
    {
        return $advertisement->delete();
    }
}
