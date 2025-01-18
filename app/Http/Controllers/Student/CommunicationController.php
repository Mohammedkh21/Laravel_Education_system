<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\Communication\CommunicationService;
use Illuminate\Http\Request;

class CommunicationController extends \App\Http\Controllers\CommunicationController
{

    public function __construct(CommunicationService $communicationService)
    {
        $this->communicationService = $communicationService;
    }

}
