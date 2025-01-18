<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Communication;
use App\Services\Teacher\Communication\CommunicationService;


class CommunicationController extends \App\Http\Controllers\CommunicationController
{

    public function __construct(CommunicationService $communicationService)
    {
        $this->communicationService = $communicationService;
    }

}
