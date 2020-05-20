<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Auth\AuthInterface;
use App\Services\ResponseService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;


class LoginController extends Controller
{
    protected $auth, $responseService;

    public function __construct(AuthInterface $auth , ResponseService $responseService)
    {

        $this->auth = $auth;
        $this->responseService = $responseService;

    }

    public function index(LoginRequest $request){
        return $this->auth->login($request->all());
    }


    public function logout(){
       return $this->auth->logout();
    }

    public function unauthenticatedResponse(){
        return $this->responseService->getErrorResource([
            'message'=>'Unauthenticated',
            'status_code' => '401'
        ]);

    }
}
