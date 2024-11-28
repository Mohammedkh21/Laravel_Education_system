<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Requests\TeacherRegisterRequest;
use App\Http\Requests\TeacherUpdateRequest;
use App\Models\Teacher;
use App\Services\Teacher\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService)
    {
    }

    public function register(TeacherRegisterRequest $request){ info(1);
        $student = $this->authService->register(
            Teacher::class,
            $request->getData()
        );
        return response()->json($student);
    }

    public function login(LoginRequest $request){
        $login = $this->authService->login(
            Teacher::class ,
            $request->getData()
        );
        return response()->json($login);
    }

    public function logout(Request $request){
        $this->authService->logout($request);
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    function index(){
        return response()->json(
            auth()->user()
        );
    }

    function update(TeacherUpdateRequest $request){
        return response()->json(
            $this->authService->update(
                auth()->user() , $request->getData()
            )
        );
    }

    function sendResitPasswordOTP(Request $request)
    {
        $request->validate(['email'=>'required|email|exists:teachers,email']);
        return response()->json(
            $this->authService->sendOTP($request->input('email'))
        );
    }

    function checkOTP(Request $request){
        $request->validate(
            [
                'email'=>'required|email|exists:teachers,email',
                'otp'=>'required|integer|digits:4'
            ]
        );
        if ($this->authService->checkOTP($request->input('email'),$request->input('otp'))){
            return response()->json(
                true
            );
        }

        return response()->json(
            false , 400
        );
    }

    function resitPassword(Request $request)
    {
        $request->validate(
            [
                'email'=>'required|email|exists:teachers,email',
                'otp'=>'required|integer|digits:4',
                'password' => 'required|string|min:8',
            ]
        );
        return response()->json(
            $this->authService->resitPassword($request->only(['email','otp','password']) , Teacher::class)
        );
    }

}
