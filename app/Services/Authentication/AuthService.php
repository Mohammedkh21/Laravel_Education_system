<?php

namespace App\Services\Authentication;


use App\Mail\OtpMail;
use App\Models\Admin;
use App\Models\Camp;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\Student\CampService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthService
{
    function register($class,$data,$camp_id){
        $data['password'] = Hash::make($data['password']);
        $user =  $class::create($data);
        auth()->login($user);
        $camp_service = $class == Student::class ?  new CampService() : new \App\Services\Teacher\Camp\CampService($user) ;
        $join_camp = $camp_service->joinCamp(Camp::find($camp_id));
        return [
          'user' => $user,
          'join_camp_request'=>$join_camp,
          'token'  => $user->createToken('MyApp')->plainTextToken
        ];
    }

//    function login($class , $data){
//        $user = $class::where('email',$data['email'])->first();
//        if (!$user || !Hash::check($data['password'] , $user->password)) {
//            throw ValidationException::withMessages([
//                'message' => ['Invalid credentials.'],
//            ]);
//        }
//        return [
//            'user' => $user,
//            'token'  => $user->createToken('MyApp')->plainTextToken
//        ];
//    }

    function login($data){
        $user =
            Student::where('email',$data['email'])->first()
                ??
            Teacher::where('email',$data['email'])->first()
                ??
            Admin::where('email',$data['email'])->first() ;

        if (!$user || !Hash::check($data['password'] , $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['Invalid credentials.'],
            ]);
        }
        return [
            'user' => $user,
            'type' => class_basename($user::class) ,
            'token'  => $user->createToken('MyApp')->plainTextToken
        ];
    }

    function logout($request){
        return $request->user()->currentAccessToken()->delete();
    }


    function update($user,$data){
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return $user;
    }

    function sendOTP($email)
    {
        $otp = rand(1000, 9999);

        $otpDB = DB::table('resit_password_otp')->updateOrInsert(
            ['email' => $email],
            [
                'otp' => $otp,
                'created_at' => now(),
            ]
        );
        Mail::to($email)->send(new OtpMail($otp));

        return $otpDB;
    }

    function checkOTP($email,$otp){
        return DB::table('resit_password_otp')->where([
            'email'=>$email,
            'otp'=>$otp
        ])->exists();
    }

    function resitPassword($data,$class){
        $otp = $this->checkOTP($data['email'],$data['otp']);
        throw_if(!$otp,\Exception::class,'invalid data',401);

        DB::table('resit_password_otp')->where('email',$data['email'])->delete();

        return $class::where('email',$data['email'])->update([
            'password'=>  Hash::make($data['password'])
        ]);
    }


}
