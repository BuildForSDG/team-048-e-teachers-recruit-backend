<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\UserExperienceRequest;
use App\Http\Resources\UserExperienceResource;
use App\Repositories\UserExperience\UserExperienceInterface;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserExperienceController extends Controller
{
    protected $userExperience, $responseService;
    public function __construct(UserExperienceInterface $userExperience, ResponseService $responseService)
    {
        $this->userExperience = $userExperience;
        $this->responseService = $responseService;
    }

    public function store(UserExperienceRequest $request){
        $params = $request->all();
        $this->userExperience->create($params);
        return $this->responseService->getSuccessResource();
    }

    public function getUserExperience(){

        $data = $this->userExperience->findByColumn([
            ['user_id', '=', Auth::id()]
        ])->get();

        $resource = UserExperienceResource::collection($data);

        return $this->responseService->getSuccessResource([
            'data' => $resource
        ]);
    }
}
