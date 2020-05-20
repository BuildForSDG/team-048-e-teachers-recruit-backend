<?php


namespace App\Repositories\Auth;

use App\Http\Resources\UserResource;
use App\Services\ResponseService;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthInterface
{
    protected $user, $responseService;

    public function __construct(User $user, ResponseService $responseService)
    {
        $this->user = $user;
        $this->responseService = $responseService;
    }

    public function create(array $params){
        $model = $this->user;
        $this->setModelProperties($model, $params);
        $model->save();
        return $model;
    }

    public function login($params){

        $user = $this->user::where('email', $params['email'])->first();

        if (!$user) {
            return $this->responseService->getErrorResource([
                'message'=> 'Invalid Email or Password' // To prevent attackers from detecting if the email is valid or not.
            ]);
        }

        //check if user is not blocked
        if ($user->blocked == 1) {
            return $this->responseService->getErrorResource([
                'message'=>  'Please contact system administrator' // Account has been blocked
            ]);
        }

        // If a user with the email was found - check if the specified password
        // belongs to this user
        if (!Hash::check($params['password'], $user->password)) {
            return $this->responseService->getErrorResource([
                'message'=>'Invalid Email or Password' // To prevent attackers from detecting if the email is valid or not.
            ]);
        }

        return $this->getAccessToken($params);

    }

    private function getAccessToken($params){

        if (Auth::attempt(['email' => $params['email'], 'password' => $params['password']])){

            $user = $this->authUser();
            $user['token'] = $user->createToken('token')->accessToken;
            $resource = new UserResource($user);
            return $this->responseService->getSuccessResource(['data'=>$resource]);
        } else {
            // Auth::logout();
            return $this->responseService->getErrorResource([
                'message'=>'Contact system administrator'
            ]);
        }
    }

    public function getAll(){
        return $this->user::latest()->get();
    }

    public function logout()
    {
        $accessToken = auth()->user()->token();

        $accessToken->revoke();

        return $this->responseService->getSuccessResource([
            'message'=>'Logout Successful'
        ]);
    }

    public function findById(int $id)
    {
        return $this->user::find($id);
    }

    public function findByColumn(array $params)
    {
        return $this->user::where($params);
    }

    public function authUser(){
        return Auth::user();
    }

    private function setModelProperties($model, $params){
        $model->first_name = strtoupper($params['first_name']);
        $model->last_name = strtoupper($params['last_name']);
        $model->other_name = isset($params['other_name']) ? strtoupper($params['other_name']): null ;
        $model->phone = $params['phone'];
        $model->email = $params['email'];
        $model->username = $params['username'];
        $model->password = bcrypt($params['password']);
        $model->email_verified_at = Carbon::now();
    }
}
