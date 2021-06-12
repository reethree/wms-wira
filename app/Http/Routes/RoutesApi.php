<?php

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function(){
    Route::get('/getSppbOnline', [
        'as' => 'nle-get-sppb',
        'uses' => 'NleController@getSppbOnline'
    ]);
    Route::get('/requestInvoice', [
        'as' => 'nle-request-invoice',
        'uses' => 'NleController@requestInvoicePlatform'
    ]);

});