<?php

namespace App\Http\Controllers\Student\Camp;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use App\Services\Student\CampService;
use Illuminate\Http\Request;

class CampController extends Controller
{
    public function __construct(public CampService $campService)
    {
    }

    public function show(){
        return response()->json(
            $this->campService->myCampInfo(auth()->user())
        );
    }

    public function join(Camp $camp){
        return response()->json(
            $this->campService->joinCamp($camp)
        );
    }

    public function getAllJoinRequest(Camp $camp){
        return response()->json(
            $this->campService->getAllJoinRequest($camp)
        );
    }

}
