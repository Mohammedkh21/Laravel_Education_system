<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use App\Services\Admin\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService)
    {
    }

    public function register(AdminRegisterRequest $request){ info(1);
        $admin = $this->authService->register(
            Admin::class,
            $request->getData()
        );
        return response()->json($admin);
    }

    public function login(LoginRequest $request){
        $login = $this->authService->login(
            Admin::class ,
            $request->getData()
        );
        return response()->json($login);
    }

    public function logout(Request $request){
        $this->authService->logout($request);
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    function sendResitPasswordOTP(Request $request)
    {
        $request->validate(['email'=>'required|email|exists:admins,email']);
        return response()->json(
            $this->authService->sendOTP($request->input('email'))
        );
    }

    function checkOTP(Request $request){
        $request->validate(
            [
                'email'=>'required|email|exists:admins,email',
                'otp'=>'required|integer|digits:4'
            ]
        );
        return response()->json(
            $this->authService->checkOTP($request->input('email'),$request->input('otp'))
        );
    }

    function resitPassword(Request $request)
    {
        $request->validate(
            [
                'email'=>'required|email|exists:admins,email',
                'otp'=>'required|integer|digits:4',
                'password' => 'required|string|min:8',
            ]
        );
        return response()->json(
            $this->authService->resitPassword($request->only(['email','otp','password']) , Admin::class)
        );
    }
    function index(){
        return response()->json(
            auth()->user()
        );
    }

    function update(AdminUpdateRequest $request){
        return response()->json(
            $this->authService->update(
                auth()->user() , $request->getData()
            )
        );
    }

}
