<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserBiodataRequest;
use App\Http\Resources\UserBiodataResource;
use App\Repositories\UserBioData\UserBioDataInterface;
use App\Services\ResponseService;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserBiodataController extends Controller
{
    protected $userBioData, $responseService;

    public function __construct(UserBioDataInterface $userBioData, ResponseService $responseService)
    {
        $this->userBioData = $userBioData;
        $this->responseService = $responseService;
    }

    public function store(UserBiodataRequest $request){

        $params = $request->all();

        $photo = Storage::putFile('public\photos', new File($params['photo'])); //save image

        $resume = Storage::putFile('public\resume', new File($params['resume'])); //save resume

        $params['photo_url'] = asset(Storage::url(str_replace('public','',$photo))); // saved photo absolute path

        $params['resume'] = asset(Storage::url(str_replace('public','',$resume))); // saved resume absolute path

        $this->userBioData->create($params);

        return $this->responseService->getSuccessResource();
    }

    public function getBioData(){

        $data = $this->userBioData->findByColumn([
            ['user_id', '=', Auth::id()]
        ])->first();

        $resource = new UserBiodataResource($data);

        return $this->responseService->getSuccessResource([
            'data' => $resource
        ]);
    }
}
