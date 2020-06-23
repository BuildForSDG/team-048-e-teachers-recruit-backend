<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\UserEducationRequest;
use App\Http\Resources\UserEducationResource;
use App\Repositories\UserEducation\UserEducationInterface;
use App\Services\ResponseService;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserEducationController extends Controller
{
    protected $userEducation, $responseService;
    public function __construct(UserEducationInterface $userEducation, ResponseService $responseService)
    {
        $this->userEducation = $userEducation;
        $this->responseService = $responseService;
    }

    public function store(UserEducationRequest $request){
        $params = $request->all();
        $this->userEducation->create($params);
        return $this->responseService->getSuccessResource();
    }

    public function getUserEducation(){

        $data = $this->userEducation->findByColumn([
            ['user_id', '=', Auth::id()]
        ])->get();

        $resource =  UserEducationResource::collection($data);

        return $this->responseService->getSuccessResource([
            'data' => $resource
        ]);
    }
}
