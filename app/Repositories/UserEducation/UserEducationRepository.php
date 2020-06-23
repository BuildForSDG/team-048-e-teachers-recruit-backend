<?php


namespace App\Repositories\UserEducation;


use App\Services\ResponseService;
use App\UserEducation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserEducationRepository implements UserEducationInterface
{
    protected $userEducation, $responseService;

    public function __construct(UserEducation $userEducation, ResponseService $responseService)
    {
        $this->userEducation = $userEducation;
        $this->responseService = $responseService;
    }

    public function create(array $params){
        $model = $this->userEducation;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function getAll(){
        return $this->userEducation::latest();
    }

    public function findById(int $id)
    {
        return $this->userEducation::find($id);
    }

    public function findByColumn(array $params)
    {
        return $this->userEducation::where($params);
    }

    private function setModelProperties($model, $params){
        $model->user_id = Auth::id();
        $model->school = $params['school'];
        $model->start = $params['start'];
        $model->end = $params['end'];
        $model->course = $params['course'];
        $model->qualification = $params['qualification'];
    }
}
