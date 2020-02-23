<?php

Route::group(['prefix' => 'tpsonline/penerimaan', 'namespace' => 'Tps'], function(){
    
    // RESPON PLP
    Route::get('/respon-plp', [
        'as' => 'tps-responPlp-index',
        'uses' => 'PenerimaanController@responPlpIndex'
    ]);
    Route::get('/respon-plp/get-xml', [
        'as' => 'tps-responPlp-get',
//        'uses' => 'PenerimaanController@responPlpGetXml'
//        'uses' => 'SoapController@GetResponPLP'
        'uses' => 'SoapController@GetResponPLP_Tujuan'
    ]);
    Route::post('/respon-plp/on-demand', [
        'as' => 'tps-responPlp-onDemand',
        'uses' => 'SoapController@GetResponPLP_onDemand'
    ]);
    Route::get('/respon-plp/edit/{id}', [
        'as' => 'tps-responPlp-edit',
        'uses' => 'PenerimaanController@responPlpEdit'
    ]);
    Route::post('/respon-plp/edit/{id}', [
        'as' => 'tps-responPlp-update',
        'uses' => 'PenerimaanController@responPlpUpdate'
    ]);
    Route::post('/respon-plp/create-joborder/{id}', [
        'as' => 'tps-responPlp-create-joborder',
        'uses' => 'PenerimaanController@responPlpCreateJoborder'
    ]);
    Route::post('/respon-plp/create-joborder-lcl/{id}', [
        'as' => 'tps-responPlp-create-joborder-lcl',
        'uses' => 'PenerimaanController@responPlpCreateJoborderLcl'
    ]);
    Route::get('/respon-plp/cetak-permohonan/{id}', [
        'as' => 'tps-responPlp-cetak-permohonan',
        'uses' => 'PenerimaanController@responPlpCetakPermohonan'
    ]);
    Route::post('/respon-plp/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\TpsTablesRepository(new App\Models\TpsResponPlp(),Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::post('/respon-plp-detail/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\TpsTablesRepository(new App\Models\TpsResponPlpDetail(),Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::post('/respon-plp-detail/crud', function()
    {
        $Eloquent = new App\Models\TpsResponPlpDetail();

        switch (Illuminate\Support\Facades\Request::get('oper'))
        {
          case 'edit':
            return $Eloquent->update(Illuminate\Support\Facades\Request::get('id'), Illuminate\Support\Facades\Request::except('id', 'oper'));
            break;
        }
    });
    
    // Respon Batal PLP
    Route::get('/respon-batal-plp', [
        'as' => 'tps-responBatalPlp-index',
        'uses' => 'PenerimaanController@responBatalPlpIndex'
    ]);
    Route::post('/respon-batal-plp/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\TpsTablesRepository(new App\Models\TpsResponBatalPlp(),Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::get('/respon-batal-plp/get-xml', [
        'as' => 'tps-responBatalPlp-get',
        'uses' => 'SoapController@GetResponBatalPLP_Tujuan'
    ]);
    Route::post('/respon-batal-plp/on-demand', [
        'as' => 'tps-responBatalPlp-onDemand',
        'uses' => 'SoapController@GetResponBatalPLP_onDemand'
    ]);
    Route::get('/respon-batal-plp/edit/{id}', [
        'as' => 'tps-responBatalPlp-edit',
        'uses' => 'PenerimaanController@responBatalPlpEdit'
    ]);
    Route::post('/respon-batal-plp/edit/{id}', [
        'as' => 'tps-responBatalPlp-update',
        'uses' => 'PenerimaanController@responBatalPlpUpdate'
    ]);
       
    // OB LCL
    Route::get('/ob-lcl', [
        'as' => 'tps-obLcl-index',
        'uses' => 'PenerimaanController@obLclIndex'
    ]);
    Route::post('/ob-lcl/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\TpsTablesRepository(new App\Models\TpsOb(),Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::get('/ob-lcl/edit/{id}', [
        'as' => 'tps-obLcl-edit',
        'uses' => 'PenerimaanController@obEdit'
    ]);
    
    // OB FCL
    Route::get('/ob-fcl', [
        'as' => 'tps-obFcl-index',
        'uses' => 'PenerimaanController@obFclIndex'
    ]);
    Route::post('/ob-fcl/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\TpsTablesRepository(new App\Models\TpsOb(),Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::get('/ob-fcl/edit/{id}', [
        'as' => 'tps-obFcl-edit',
        'uses' => 'PenerimaanController@obEdit'
    ]);
    
    Route::get('/ob/get-xml', [
        'as' => 'tps-ob-get',
//        'uses' => 'PenerimaanController@obGetXml'
        'uses' => 'SoapController@GetOB'
    ]);
    
    // SPJM
    Route::get('/spjm', [
        'as' => 'tps-spjm-index',
        'uses' => 'PenerimaanController@spjmIndex'
    ]);
    Route::get('/spjm/edit/{id}', [
        'as' => 'tps-spjm-edit',
        'uses' => 'PenerimaanController@spjmEdit'
    ]);
    Route::post('/spjm/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\TpsTablesRepository(new App\Models\TpsSpjm(),Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::get('/spjm/get-xml', [
        'as' => 'tps-spjm-get',
//        'uses' => 'PenerimaanController@spjmGetXml'
        'uses' => 'SoapController@GetSPJM'
    ]);
    
    //Dok Manual
    Route::get('/dok-manual', [
        'as' => 'tps-dokManual-index',
        'uses' => 'PenerimaanController@dokManualIndex'
    ]);
    Route::get('/dok-manual/edit/{id}', [
        'as' => 'tps-dokmanual-edit',
        'uses' => 'PenerimaanController@dokManualEdit'
    ]);
    Route::post('/dok-manual/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\TpsTablesRepository(new App\Models\TpsDokManual(),Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::get('/dok-manual/get-xml', [
        'as' => 'tps-dokManual-get',
//        'uses' => 'PenerimaanController@sppbPibGetXml'
        'uses' => 'SoapController@GetDokumenManual'
//        'uses' => 'SoapController@demo'
    ]);
    
    //SPPB PIB
    Route::get('/sppb-pib', [
        'as' => 'tps-sppbPib-index',
        'uses' => 'PenerimaanController@sppbPibIndex'
    ]);
    Route::post('/sppb-pib/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\TpsTablesRepository(new App\Models\TpsSppbPib(),Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::get('/sppb-pib/edit/{id}', [
        'as' => 'tps-sppbPib-edit',
        'uses' => 'PenerimaanController@sppbPibEdit'
    ]);
    Route::post('/sppb-pib/edit/{id}', [
        'as' => 'tps-sppbPib-update',
        'uses' => 'PenerimaanController@sppbPibUpdate'
    ]);
    Route::get('/sppb-pib/get-xml', [
        'as' => 'tps-sppbPib-get',
//        'uses' => 'PenerimaanController@sppbPibGetXml'
        'uses' => 'SoapController@GetImporPermit'
//        'uses' => 'SoapController@GetImpor_SPPB'
    ]);
    Route::post('/sppb-pib/upload-xml', [
        'as' => 'tps-sppbPib-upload',
        'uses' => 'SoapController@GetImpor_SPPB'
    ]);
    
    //SPPB BEA CUKAI
    Route::get('/sppb-bc', [
        'as' => 'tps-sppbBc-index',
        'uses' => 'PenerimaanController@sppbBcIndex'
    ]);
    Route::post('/sppb-bc/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\TpsTablesRepository(new App\Models\TpsSppbBc(),Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::get('/sppb-bc/edit/{id}', [
        'as' => 'tps-sppbBc-edit',
        'uses' => 'PenerimaanController@sppbBcEdit'
    ]);
    Route::post('/sppb-bc/edit/{id}', [
        'as' => 'tps-sppbBc-update',
        'uses' => 'PenerimaanController@sppbBcUpdate'
    ]);
    Route::get('/sppb-bc/get-xml', [
        'as' => 'tps-sppbBc-get',
//        'uses' => 'PenerimaanController@sppbBcGetXml'
        'uses' => 'SoapController@GetBC23Permit'
    ]);
    
    //INFO NOMOR BC11
    Route::get('/infonomor-bc', [
        'as' => 'tps-infoNomorBc-index',
        'uses' => 'PenerimaanController@infoNomorBcIndex'
    ]);
    Route::post('/infonomor-bc/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\TpsTablesRepository(new App\Models\TpsGetInfoNomorBc(),Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::post('/infonomor-bc/get-xml', [
        'as' => 'tps-infoNomorBc-get',
//        'uses' => 'PenerimaanController@sppbBcGetXml'
        'uses' => 'SoapController@GetInfoNomorBc'
    ]);
});