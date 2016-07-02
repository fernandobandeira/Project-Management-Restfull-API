<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'api'], function () {
    Route::post('oauth/access_token', function() {
       return Response::json(Authorizer::issueAccessToken());
    });

    Route::group(['middleware' => 'oauth'], function() {
        Route::resource('client', 'ClientController', ['except'=>['create','edit']]);
        Route::resource('project', 'ProjectController', ['except'=>['create','edit']]);
        Route::get('/project/{id}/members', 'ProjectController@members');
        Route::resource('project.note', 'ProjectNoteController', ['except'=>['create','edit']]);
        Route::resource('project.task', 'ProjectTaskController', ['except'=>['create','edit']]);
    });
});
