<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EmailVerificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function sendVerificationEmail(Request $request = null, User $user = null)
    {
        if(!is_null($user)){

            if($user->hasVerifiedEmail()) return [ 'message' => 'Already Verified'];

            else return $user->sendEmailVerificationNotification();

        } else{

            if($request->user()->hasVerifiedEmail()) return [ 'message' => 'Already Verified'];
        }

        $request->user()->sendEmailVerificationNotification();

        return ['status' => 'verification-link-sent'];
    }

    public function verify(Request $request)
    {

        if($request->user()->hasVerifiedEmail()) return ['message' => 'Email already verified'];

        else throw new BadRequestException("Seu e-mail está pendente de confirmação. Acesse sua caixa de entrada e confirme-o.", 400);

        return [ 'message' => 'Email has been verified' ];
    }
}
