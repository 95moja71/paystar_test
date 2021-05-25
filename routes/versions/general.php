<?php


use Illuminate\Support\Facades\Route;


Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::group(['middleware' => 'auth:api'], function ()  {
    Route::post('user', function () {
        return \Auth::user();
    });
});
