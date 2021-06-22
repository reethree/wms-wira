<?php

Route::group(['prefix'=>'accurate'], function(){
    Route::get('/oauth', [
        'as'=>'accurate-oauth',
        'uses'=>'AccurateController@oauth'
    ]);

    Route::get('/get-session', [
        'as'=>'accurate-get-session',
        'uses'=>'AccurateController@getSession'
    ]);

    Route::post('/upload', [
        'as'=>'accurate-upload',
        'uses'=>'AccurateController@saveInvoice'
    ]);
});