<?php

namespace App\Http\Controllers\V1\Auth;

use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Repositories\Auth\AuthInterface;
use App\Repositories\VerifyEmail\VerifyEmailInterface;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    protected  $auth, $responseService,$verifyEmail;

    public function __construct(AuthInterface $auth, ResponseService $responseService,
                                VerifyEmailInterface $verifyEmail)
    {
        $this->auth = $auth;
        $this->responseService = $responseService;
        $this->verifyEmail = $verifyEmail;
    }

    public function basicFormStepOneValidation(RegistrationRequest $request){

        $token = getUniqueToken();

        $email = $request->email;

        //check if email already exist, if it exist update time and token
        $checkIfEmailExist = $this->verifyEmail->findByColumn(['email'=>$email])->first();
        if ($checkIfEmailExist){
            $checkIfEmailExist->time = now();
            $checkIfEmailExist->token = $token;
            $checkIfEmailExist->save();
        }else{
            $this->verifyEmail->create($email,$token);
        }

        Mail::to("$email")->send(new VerifyEmail($token));
        return $this->responseService->getSuccessResource([
            'message' => "Please check your email we've sent you a verification code"
        ]);
    }

    public function verifyEmail(VerifyEmailRequest $request){

        return $this->verifyEmail->verifyEmail($request->all());
    }

    public function create(RegistrationRequest $request){

        //check if email has been verify
        $validate = $this->verifyEmail->findByColumn(['email' => $request["email"],'verified'=>true])->first();
        if(!$validate){
            return $this->responseService->getErrorResource(['message'=>'Email not verified' ,'field_errors' => [
                'email'=>"Mail not verified ".$request["email"]
            ]
            ]);
        }

        $params = $request->all();

        try {

            DB::beginTransaction();

            //store new user
            $this->auth->create($params);

            //delete email from verification table
            $this->verifyEmail->findByColumn(['email' => $request["email"]])->delete();

            DB::commit();

            //Login new user
           return $this->auth->login($params);

        }catch (\Exception $e){

            DB::rollBack();

            return $this->responseService->getErrorResource([
                'message' => 'OOPS!!! Something went wrong, please contact system admin '.$e->getMessage()
            ]);
        }

    }

}
