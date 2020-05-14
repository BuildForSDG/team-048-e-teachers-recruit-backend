<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("v1/user")->group(function(){
    Route::namespace("V1\Auth")->group(function(){

        Route::post("/login", 'LoginController@index');
        Route::get('/login', 'LoginController@unauthenticatedResponse')->name('login');

        Route::prefix("registration")->group(function(){
            Route::post("/basic-form-validation", 'RegistrationController@basicFormStepOneValidation');
            Route::post('/email-verification','RegistrationController@verifyEmail');
            Route::post("/create", 'RegistrationController@create');
        });

        Route::prefix("reset-password")->group(function(){
            Route::post("/request", 'ResetPasswordController@requestResetForgotPassword');
            Route::post('/verify-token','ResetPasswordController@verifyToken');
            Route::post("/", 'ResetPasswordController@resetPassword');
        });

    });
});
