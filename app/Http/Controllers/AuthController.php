<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Student;
use App\Services\Student\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService)
    {
    }
    public function login(LoginRequest $request)
    {
        $login = $this->authService->login(
            $request->getData()
        );
        return response()->json($login);
    }

    public function authInfo(){
        return response()->json([
            'user'=>auth()->user(),
            'type'=>class_basename(auth()->user())
        ]);
    }
}
