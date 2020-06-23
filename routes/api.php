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

//Auth protected route
Route::group(['middleware' => ['auth:api']], function () {

    Route::prefix('v1/user')->group(function (){


        Route::prefix('user-bio-data')->group(function (){
            Route::get('/','V1\UserBiodataController@getBioData');
            Route::post('/','V1\UserBiodataController@store');
        });

        Route::prefix('user-education')->group(function (){
            Route::get('/','V1\UserEducationController@getUserEducation');
            Route::post('/','V1\UserEducationController@store');
        });

        Route::prefix('user-experience')->group(function (){
            Route::get('/','V1\UserExperienceController@getUserExperience');
            Route::post('/','V1\UserExperienceController@store');
        });

        Route::prefix('user-skills')->group(function (){
            Route::get('/','V1\UserSkillsController@getUserSkills');
            Route::post('/','V1\UserSkillsController@store');
        });


    });
});
