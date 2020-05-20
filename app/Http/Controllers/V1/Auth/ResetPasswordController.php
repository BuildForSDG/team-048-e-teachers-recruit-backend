<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Mail\ForgotPassword;
use App\Repositories\Auth\AuthInterface;
use App\Repositories\VerifyEmail\VerifyEmailInterface;
use App\Http\Controllers\Controller;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    protected $fPassword,$responseService,$auth;

    public function __construct(VerifyEmailInterface $fPassword, ResponseService $responseService, AuthInterface $auth)
    {
        $this->fPassword = $fPassword;
        $this->responseService = $responseService;
        $this->auth = $auth;
    }

    public  function requestResetForgotPassword(ForgotPasswordRequest $request){

        $verification_token = getUniqueToken();

        $email = $request->email;

        //check if email already exist, if it exist update time and token
        $checkIfEmailExist = $this->fPassword->findByColumn(['email'=>$email])->first();
        if ($checkIfEmailExist){
            $checkIfEmailExist->time = now();
            $checkIfEmailExist->token = $verification_token;
            $checkIfEmailExist->save();
        }else{
            $this->fPassword->create($email,$verification_token);
        }

        Mail::to("$email")->send(new ForgotPassword($verification_token));
        return $this->responseService->getSuccessResource([
            'message' => "Please check your email we've sent you a verification code"
        ]);
    }

    public function verifyToken(VerifyEmailRequest $request){
        return $this->fPassword->verifyEmail($request->all());
    }

    public  function resetPassword(ResetPasswordRequest $request){

        $checkIfEmailExist = $this->fPassword->findByColumn([
            'email'=>$request->email,
            'verified'=>true,
            'token'=>$request->token])->first();

        $findEmail = $this->auth->findByColumn(['email'=>$request->email])->first();

        if (!$findEmail || !$checkIfEmailExist)
            return $this->responseService->getErrorResource([
                'message' => "OOPS!!! Something went wrong, please request for a new password reset."
            ]);

        $findEmail->password = bcrypt($request->password);
        $findEmail->save();

        $checkIfEmailExist->delete();

        return $this->responseService->getSuccessResource([
            'message' => "Password reset successful"
        ]);
    }
}
