<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\Communication\CommunicationService;
use Illuminate\Http\Request;

class CommunicationController extends \App\Http\Controllers\CommunicationController
{
    //
    public function __construct(CommunicationService $communicationService)
    {
        $this->communicationService = $communicationService;
    }
}
