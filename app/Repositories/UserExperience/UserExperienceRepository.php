<?php


namespace App\Repositories\UserExperience;


use App\Services\ResponseService;
use App\UserExperience;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserExperienceRepository implements UserExperienceInterface
{
    protected $userExperience, $responseService;

    public function __construct(UserExperience $userExperience, ResponseService $responseService)
    {
        $this->userExperience = $userExperience;
        $this->responseService = $responseService;
    }

    public function create(array $params){
        $model = $this->userExperience;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function getAll(){
        return $this->userExperience::latest();
    }

    public function findById(int $id)
    {
        return $this->userExperience::find($id);
    }

    public function findByColumn(array $params)
    {
        return $this->userExperience::where($params);
    }

    private function setModelProperties($model, $params){
        $model->user_id = Auth::id();
        $model->organization = $params['organization'];
        $model->role = $params['role'];
        $model->start = $params['start'];
        $model->end = $params['end'];
        $model->location = $params['location'];
        $model->current = $params['current'] ?? true;
    }
}
