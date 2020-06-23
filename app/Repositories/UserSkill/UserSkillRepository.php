<?php


namespace App\Repositories\UserSkill;


use App\Services\ResponseService;
use App\UserSkills;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserSkillRepository implements UserSkillInterface
{
    protected $userSkills, $responseService;

    public function __construct(UserSkills $userSkills, ResponseService $responseService)
    {
        $this->userSkills = $userSkills;
        $this->responseService = $responseService;
    }

    public function create(array $params){
        $model = $this->userSkills;
        $check = $this->findByColumn(
            [
                ['user_id','=',Auth::id()],
                ['skill_id', '=', $params['skill']]
            ]
        )->first();
        if ($check) return $check;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function getAll(){
        return $this->userSkills::latest();
    }

    public function findById(int $id)
    {
        return $this->userSkills::find($id);
    }

    public function findByColumn(array $params)
    {
        return $this->userSkills::where($params);
    }

    private function setModelProperties($model, $params){
        $model->user_id = Auth::id();
        $model->skill_id = $params['skill'];
    }
}
