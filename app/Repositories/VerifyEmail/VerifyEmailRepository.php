<?php

namespace App\Repositories\VerifyEmail;

use App\Services\ResponseService;
use App\EmailVerification;
use Carbon\Carbon;

class VerifyEmailRepository implements VerifyEmailInterface{

    protected $responseService, $model;

    public function __construct(ResponseService $responseService, EmailVerification $model){
        $this->responseService = $responseService;
        $this->model = $model;
    }

    public function create($email,$token){

        $model = $this->model;
        $this->setModelProperties($model, $email, $token);
        $model->save();
    }

    private function setModelProperties($model, $email, $token){
        $model->time = now();
        $model->email = $email;
        $model->token = $token;
    }

    public function findByColumn(array $params){
       return $this->model::where($params);
    }

    public function verifyEmail($params){

        //check if email and token tally
        $checkEmail = $this->findByColumn(['email'=>$params['email'],'token'=>$params['token']])->first();
        if (!$checkEmail)
            return $this->responseService->getErrorResource([
                'message'=>'Invalid email or token'
            ]);

        //check if token has not expire
       $isExpired = $this->checkExpiry($checkEmail->time);

        if($isExpired){
            return $this->responseService->getErrorResource([
                'message'=>'token expired'
            ]);
        }

        //update verified
        $checkEmail->verified = true;
        $checkEmail->save();

        return $this->responseService->getSuccessResource([
            'message'=>'Email verification successful'
        ]);

    }

    private function checkExpiry($time){

        $dt = Carbon::parse($time)->addMinutes(10);
        $ct = Carbon::now();
        return $ct->gt($dt);
    }

}
