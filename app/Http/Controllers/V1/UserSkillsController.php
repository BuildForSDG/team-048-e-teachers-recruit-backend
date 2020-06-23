<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\UserSkillsRequest;
use App\Http\Resources\UserSkillsResource;
use App\Repositories\UserSkill\UserSkillInterface;
use App\Services\ResponseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserSkillsController extends Controller
{

    protected $userSkill, $responseService;
    public function __construct(UserSkillInterface $userSkill, ResponseService $responseService)
    {
        $this->userSkill = $userSkill;
        $this->responseService = $responseService;
    }

    public function store(UserSkillsRequest $request){
        $params = $request->all();
        $this->userSkill->create($params);
        return $this->responseService->getSuccessResource();
    }

    public function getUserSkills(){

        $data = $this->userSkill->findByColumn([
            ['user_id', '=', Auth::id()]
        ])->get();

        $resource =  UserSkillsResource::collection($data);

        return $this->responseService->getSuccessResource([
            'data' => $resource
        ]);
    }
}
