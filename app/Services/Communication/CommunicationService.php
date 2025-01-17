<?php

namespace App\Services\Communication;

class CommunicationService
{

    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    function getAll()
    {
        return $this->user->communications;
    }

    function show($communication)
    {
        return $this->user->communications()->find($communication->id);
    }

    function store($data)
    {
        return $this->user->communications()->create($data);
    }

    function update($data,$communication)
    {
        return $this->user->communications()->find($communication->id)->update($data);
    }

    function delete($communication)
    {
        return $this->user->communications()->find($communication->id)->delete();
    }
}
