<?php


namespace App\Repositories\UserBioData;


use App\Services\ResponseService;
use App\UserBioData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserBioDataRepository implements UserBioDataInterface
{
    protected $userBioData, $responseService;

    public function __construct(UserBioData $userBioData, ResponseService $responseService)
    {
        $this->userBioData = $userBioData;
        $this->responseService = $responseService;
    }

    public function create(array $params){
        $model = $this->userBioData;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function getAll(){
        return $this->userBioData::latest();
    }

    public function findById(int $id)
    {
        return $this->userBioData::find($id);
    }

    public function findByColumn(array $params)
    {
        return $this->userBioData::where($params);
    }

    private function setModelProperties($model, $params){
        $model->user_id = Auth::id();
        $model->about = $params['about'];
        $model->state_id = $params['state'];
        $model->lga = $params['lga'];
        $model->resume = $params['resume'];
        $model->photo_url = $params['photo_url'];
    }
}
