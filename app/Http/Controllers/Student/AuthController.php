<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StudentRegisterRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Models\Student;
use App\Services\Student\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService)
    {
    }

    public function register(StudentRegisterRequest $request){
        $student = $this->authService->register(
            Student::class,
            $request->getData()
        );
        return response()->json($student);
    }

//    public function login(LoginRequest $request){
//        $login = $this->authService->login(
//            Student::class ,
//            $request->getData()
//        );
//        return response()->json($login);
//    }

    public function logout(Request $request){
        $this->authService->logout($request);
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    function index(){
        return response()->json(
            auth()->user()
        );
    }

    function update(StudentUpdateRequest $request){
        return response()->json(
            $this->authService->update(
                auth()->user() , $request->getData()
            )
        );
    }

    function sendResitPasswordOTP(Request $request)
    {
        $request->validate(['email'=>'required|email|exists:students,email']);
        return response()->json(
            $this->authService->sendOTP($request->input('email'))
        );
    }

    function checkOTP(Request $request){
        $request->validate(
            [
                'email'=>'required|email|exists:students,email',
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
                'email'=>'required|email|exists:students,email',
                'otp'=>'required|integer|digits:4',
                'password' => 'required|string|min:8',
            ]
        );
        return response()->json(
            $this->authService->resitPassword($request->only(['email','otp','password']) , Student::class)
        );
    }

}
