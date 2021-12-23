<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function sendVerificationEmail(Request $request = null, User $user = null)
    {
       // dd(!is_null($user));
        if(!is_null($user)){
            //dd("entrou aqui");
            if($user->hasVerifiedEmail())
                return [ 'message' => 'Already Verified'];
            else
                return $user->sendEmailVerificationNotification();
        } else{
            if($request->user()->hasVerifiedEmail()) return [ 'message' => 'Already Verified'];
        }


        $request->user()->sendEmailVerificationNotification();

        return ['status' => 'verification-link-sent'];
    }

    public function verify(EmailVerificationRequest $request)
    {
        if($request->user()->hasVerifiedEmail()) return ['message' => 'Email already verified'];

        if($request->user()->markEmailAsVerified()) event(new Verified($request-user()));

        return [ 'message' => 'Email has been verified' ];
    }
}
