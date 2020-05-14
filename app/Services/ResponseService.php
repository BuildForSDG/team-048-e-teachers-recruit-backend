<?php

namespace App\Services;

use Illuminate\Support\Arr;


class ResponseService{

    public function getErrorResource(array $attributes = null)
    {

        $status_code = Arr::get($attributes,"status_code",422);
        $message = Arr::get($attributes,"message","Failed");

        $non_field_errors = Arr::get($attributes,"non_field_errors");

        $field_errors = Arr::get($attributes,"field_errors");

        $error = [
            "status" => $status_code,
            "message" => $message,
            "code" => Arr::get($attributes,"code","SYS_01")//ErrorCode::AUTH_01
        ];

        if($non_field_errors)  Arr::set($error,"non_field_errors",$non_field_errors);

        if($field_errors)  Arr::set($error,"field_errors",$field_errors);



        return response()->json([
            "status" => false,
            "message" => $message,
            "data" => $error
        ],$status_code);
    }

    public function getSuccessResource(array $attributes = null)
    {

        $status_code = Arr::get($attributes,"status_code",200);
        $message = Arr::get($attributes,"message","Successful");
        $data = Arr::get($attributes,"data");

        $result = [
            "status" => true,
            "message" => $message,
            "data" => [
                "status" => $status_code,
                "message" => $message
            ]
        ];

        if($data) {
            Arr::set($result,"data",$data);
        }

        return response()->json($result,$status_code);
    }

//
//    public function getJWTAuthFailedResource($attributes=null)
//    {
//        return $this->getErrorResource([
//            "message" => "Access Unauthorized",
//            "code" => ErrorCodes::AUTH_01,
//            "status_code" => 401,
//        ]);
//    }
//
//    public function getAuthFailedUserResource($attributes=null)
//    {
//        return $this->getErrorResource([
//            "message" => Arr::get($attributes,"message","Invalid Account Credential"),
//            "code" => ErrorCodes::USR_01
//        ]);
//    }
//
//    public function getLoginErrorResource($attributes=null)
//    {
//        $error = [
//            "field_errors" => Arr::get($attributes,"field_errors",null),
//            "message" => Arr::get($attributes,"message","login failed"),
//            "code" => ErrorCodes::USR_01
//        ];
//        $non_field_errors = Arr::get($attributes,"non_field_errors",null);
//        $field_errors = Arr::get($attributes,"field_errors",null);
//        if($non_field_errors) Arr::set($error,"non_field_errors",$non_field_errors);
//        if($field_errors) Arr::set($error,"field_errors",$field_errors);
//
//        return $this->getErrorResource($error);
//    }
//
//
//    public function getAccountCreationError($attributes=null)
//    {
//        return $this->getErrorResource([
//            "message" => Arr::get($attributes,"message","Account Could not be created"),
//            "code" => ErrorCodes::USR_02,
//            "status_code" => 400,
//        ]);
//    }
//
//    public function getPaystackError($attributes=null)
//    {
//        return $this->getErrorResource([
//            "status"    => Arr::get($attributes,"status","false"),
//            "message" => Arr::get($attributes,"message","Could not create recipient"),
//            "code" => ErrorCodes::PAYSTACK_USR_01,
//            "status_code" => 400,
//        ]);
//    }
//
//    public function getAuthSuccessUserResource(User $user)
//    {
//        return new LoginResource($user);
//    }
//
//    public function getUserResource(User $user)
//    {
//        return [
//            "id"=> $user->user_id_id,
//            "first_name"=> $user->first_name,
//            "last_name"=> $user->last_name,
//            "email"=> $user->email,
//            "mobile_number"=> $user->mobile_number
//        ];
//    }
}
