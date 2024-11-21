<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Mail;
use Auth;
use Validator;
use Session;

class OtpController extends Controller
{
    public function loginwithotppost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50'
        ]);

        $checkUser = User::where('email', $request->email)->first();
        if (is_null($checkUser)){
            return redirect()->back()->with('error','Your Email Address is not associated with us.');
        }else{
            $otp = rand(100000,999999);
            $userUpdate = User::where('email', $request->email)->update([
                'otp'   =>$otp,
            ]);
            Mail::send('emails.loginWithOtpEmail',['otp'=>$otp], function($message) use($request){
               $message->to($request->email);
               $message->subject('login with otp -anis');
            });
        }
        return redirect(route('confirm.login.with.otp'))->with('email',$request->email);

    }

    public function otpVerify(Request $request){
        $request->validate(
            [
                'email' => 'required|email|max:50',
                'otp' => 'required|numeric',
            ],
            [
                'email.required' => 'The email field is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.max' => 'The email must not exceed 50 characters.',
                'otp.required' => 'The OTP field is required.',
                'otp.numeric' => 'The OTP must be a numeric value.',
            ]);
        $checkUser = User::where('email', $request->email)->first();
        if (is_null($checkUser)){
            return redirect()->back()->with('error','Your Email Address is not associated with us.');
        }else{
            $verifyUser = User::where('otp', $request->otp)->first();
            if (is_null($verifyUser)){
                return redirect()->back()->with('error','Your OTP is not Match.');
            }else{
                User::where('email', $request->email)->update([
                    'otp'   =>null,
                    ]);
                Auth::login($checkUser);
                return redirect('/home');
            }
        }
    }
}
