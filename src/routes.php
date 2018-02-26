<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('irisit_filestash.base_path'), 'namespace' => 'Irisit\Filestash\Http\Controllers', 'middleware' => 'web'], function () {

    Route::get('/', function () {
        return [
            'api' => '0.1.0',
            'provider' => 'filestash'
        ];
    })->name('filestash.index');

    Route::group(['prefix' => config('irisit_filestash.api_path'), 'namespace' => 'Api'], function () {

        Route::any('/', 'FilestashController@handleRequests')->name('filestash.handle_requests');

    });

    Route::group(['prefix' => config('irisit_filestash.admin_path'), 'namespace' => 'Admin', 'middleware' => ['auth', 'role:' . config('irisit_filestash.admin_allowed_roles')]], function () {


    });

});