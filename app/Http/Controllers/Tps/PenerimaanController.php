<?php

namespace App\Http\Controllers\Tps;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
   
    
    public function responPlpIndex()
    {
        if ( !$this->access->can('show.tps.responPlp.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index TPS Respon PLP', 'slug' => 'show.tps.responPlp.index', 'description' => ''));
        
        $data['page_title'] = "TPS Respon PLP";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'TPS Respon PLP'
            ]
        ];        
        
        return view('tpsonline.index-respon-plp')->with($data);
    }
    
    public function responBatalPlpIndex()
    {
        if ( !$this->access->can('show.tps.responBatalPlp.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index TPS Respon Batal PLP', 'slug' => 'show.tps.responBatalPlp.index', 'description' => ''));
        
        $data['page_title'] = "TPS Respon Batal PLP";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'TPS Respon Batal PLP'
            ]
        ];        
        
        return view('tpsonline.index-respon-batal-plp')->with($data);
    }
    
    public function obLclIndex()
    {
        if ( !$this->access->can('show.tps.obLcl.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index TPS OB LCL', 'slug' => 'show.tps.obLcl.index', 'description' => ''));
        
        $data['page_title'] = "TPS OB LCL";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'TPS OB LCL'
            ]
        ];        
        
        return view('tpsonline.index-ob-lcl')->with($data);
    }
    
    public function obFclIndex()
    {
        if ( !$this->access->can('show.tps.obFcl.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index TPS OB FCL', 'slug' => 'show.tps.obFcl.index', 'description' => ''));
        
        $data['page_title'] = "TPS OB FCL";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'TPS OB FCL'
            ]
        ];        
        
        return view('tpsonline.index-ob-fcl')->with($data);
    }
    
    public function spjmIndex()
    {
        if ( !$this->access->can('show.tps.spjm.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index TPS SPJM', 'slug' => 'show.tps.spjm.index', 'description' => ''));
        
        $data['page_title'] = "TPS SPJM";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'TPS SPJM'
            ]
        ];        
        
        return view('tpsonline.index-spjm')->with($data);
    }
    
    public function dokManualIndex()
    {
        if ( !$this->access->can('show.tps.dokManual.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index TPS Dok Manual', 'slug' => 'show.tps.dokManual.index', 'description' => ''));
        
        $data['page_title'] = "TPS Dokumen Manual";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'TPS Dokumen Manual'
            ]
        ];        
        
        $data['kode_doks'] = \App\Models\KodeDok::get(); 
        
        return view('tpsonline.index-dok-manual')->with($data);
    }
    
    public function dokPabeanIndex()
    {
        if ( !$this->access->can('show.tps.dokPabean.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index TPS Dok Pabean', 'slug' => 'show.tps.dokPabean.index', 'description' => ''));
        
        $data['page_title'] = "TPS Dokumen Pabean";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'TPS Dokumen Pabean'
            ]
        ];        
        
        $data['kode_doks'] = \App\Models\KodeDok::get(); 
        
        return view('tpsonline.index-dok-pabean')->with($data);
    }
    
    public function sppbPibIndex()
    {
        if ( !$this->access->can('show.tps.sppbPib.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index TPS SPPB PIB', 'slug' => 'show.tps.sppbPib.index', 'description' => ''));
        
        $data['page_title'] = "TPS SPPB PIB";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'TPS SPPB PIB'
            ]
        ];        
        
        return view('tpsonline.index-sppb-pib')->with($data);
    }
    
    public function sppbBcIndex()
    {
        if ( !$this->access->can('show.tps.sppbBc.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index TPS SPPB BC', 'slug' => 'show.tps.sppbBc.index', 'description' => ''));
        
        $data['page_title'] = "TPS SPPB BC23";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'TPS SPPB BC23'
            ]
        ];        
        
        return view('tpsonline.index-sppb-bc')->with($data);
    }
    
    public function infoNomorBcIndex()
    {
        if ( !$this->access->can('show.tps.infoNomorBc.index') ) {
            return view('errors.no-access');
        }
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index TPS Info Nomor BC11', 'slug' => 'show.tps.infoNomorBc.index', 'description' => ''));
        
        $data['page_title'] = "TPS Info Nomor BC11";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'TPS Info Nomor BC11'
            ]
        ];        
        
        return view('tpsonline.index-infonomor-bc')->with($data);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function responPlpEdit($id)
    {
        if ( !$this->access->can('show.tps.responPlp.edit') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Edit TPS Respon PLP', 'slug' => 'show.tps.responPlp.edit', 'description' => ''));
        
        $data['page_title'] = "Edit TPS Respon PLP";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('tps-responPlp-index'),
                'title' => 'Edit TPS Respon PLP'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
//        $plpid = \App\Models\TpsResponPlpDetail::where('tps_responplptujuandetailxml_pk', $id)->pluck('tps_responplptujuanxml_fk');
        
        $data['respon'] = \App\Models\TpsResponPlp::find($id);
        
        return view('tpsonline.edit-respon-plp')->with($data);
    }
    
    public function responBatalPlpEdit($id)
    {
        if ( !$this->access->can('show.tps.responBatalPlp.edit') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Edit TPS Respon Batal PLP', 'slug' => 'show.tps.responBatalPlp.edit', 'description' => ''));
        
        $data['page_title'] = "Edit TPS Respon Batal PLP";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('tps-responBatalPlp-index'),
                'title' => 'Edit TPS Respon Batal PLP'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['respon'] = \App\Models\TpsResponBatalPlp::find($id);
        
        return view('tpsonline.edit-respon-batal-plp')->with($data);
    }
    
    public function obEdit($id)
    {
        if ( !$this->access->can('show.tps.ob.edit') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Edit TPS OB', 'slug' => 'show.tps.ob.edit', 'description' => ''));
        
        $data['page_title'] = "Edit OB";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Edit OB'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['ob'] = \App\Models\TpsOb::find($id);
        
        return view('tpsonline.edit-ob')->with($data);
    }
    
    public function spjmEdit($id)
    {
        if ( !$this->access->can('show.tps.spjm.edit') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Edit TPS SPJM', 'slug' => 'show.tps.spjm.edit', 'description' => ''));
        
        $data['page_title'] = "Edit SPJM";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('tps-spjm-index'),
                'title' => 'TPS SPJM'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['spjm'] = \App\Models\TpsSpjm::find($id);
        
        return view('tpsonline.edit-spjm')->with($data);
    }
    
    public function sppbPibEdit($id)
    {
        if ( !$this->access->can('show.tps.sppbPib.edit') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Edit TPS SPPB PIB', 'slug' => 'show.tps.sppbPib.edit', 'description' => ''));
        
        $data['page_title'] = "Edit SPPB PIB";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('tps-sppbPib-index'),
                'title' => 'TPS SPPB PIB'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['sppb'] = \App\Models\TpsSppbPib::find($id);
        
        return view('tpsonline.edit-sppb-pib')->with($data);
    }
    
    public function sppbBcEdit($id)
    {
        if ( !$this->access->can('show.tps.sppbBc.edit') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Edit TPS SPPB BC', 'slug' => 'show.tps.sppbBc.edit', 'description' => ''));
        
        $data['page_title'] = "Edit SPPB BC23";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('tps-sppbBc-index'),
                'title' => 'TPS SPPB BC23'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['sppb'] = \App\Models\TpsSppbBc::find($id);
        
        return view('tpsonline.edit-sppb-bc')->with($data);
    }
    
    public function dokManualEdit($id)
    {
        if ( !$this->access->can('show.tps.dokmanual.edit') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Edit TPS DOKUMEN MANUAL', 'slug' => 'show.tps.dokmanual.edit', 'description' => ''));
        
        $data['page_title'] = "Edit Dokumen Manual";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('tps-dokManual-index'),
                'title' => 'TPS DOKUMEN MANUAL'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['dokmanual'] = \App\Models\TpsDokManual::find($id);
        
        return view('tpsonline.edit-dok-manual')->with($data);
    }
    
    public function dokPabeanEdit($id)
    {
        if ( !$this->access->can('show.tps.dokpabean.edit') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Edit TPS DOKUMEN PABEAN', 'slug' => 'show.tps.dokpabean.edit', 'description' => ''));
        
        $data['page_title'] = "Edit Dokumen Pabean";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('tps-dokpabean-index'),
                'title' => 'TPS DOKUMEN PABEAN'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['dokpabean'] = \App\Models\TpsDokPabean::find($id);
        
        return view('tpsonline.edit-dok-pabean')->with($data);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    
    public function responPlpUpdate(Request $request, $id)
    {
        
    }
    
    public function responBatalPlpUpdate(Request $request, $id)
    {
        
    }
    
    public function sppbPibUpdate(Request $request, $id)
    {
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }    
    
    public function responPlpGetXml()
    {
        $xml = simplexml_load_file(url('xml/GetPlp.xml'));
        $header = array();
        $details = [];
        foreach($xml->children() as $child) {
            foreach($child as $key => $value) {
                if($key == 'header' || $key == 'HEADER'){
                    $header[] = $value;
                }else{
                    foreach ($value as $detail):
                        $details[] = $detail;
                    endforeach;
                }
            }
        }
        
        // INSERT DATA
        $respon = new \App\Models\TpsResponPlp;
        foreach ($header[0] as $key=>$value):
            $respon->$key = $value;
        endforeach;
        $respon->TGL_UPLOAD = date('Y-m-d H:i:s');
        $respon->save();
        
        $plp_id = $respon->tps_responplptujuanxml_pk;

        foreach ($details as $detail):     
            $respon_detail = new \App\Models\TpsResponPlpDetail;
            $respon_detail->tps_responplptujuanxml_fk = $plp_id;
            foreach($detail as $key=>$value):
                $respon_detail->$key = $value;
            endforeach;
            $respon_detail->save();
        endforeach;
        
        return back()->with('success', 'Get Respon PLP has been success.');
        
    }
    
    public function responBatalPlpGetXml()
    {
        
    }
    
    public function obGetXml()
    {
        $xml = simplexml_load_file(url('xml/GetImpOB20161108012409.xml'));
        $ob = array();
        foreach($xml->children() as $child) {
            $ob[] = $child;
        }
        
        // INSERT DATA       
        foreach ($ob as $data):
            $obinsert = new \App\Models\TpsOb;
            foreach ($data as $key=>$value):
                if($key == 'KODE_KANTOR' || $key == 'kode_kantor'){ $key='KD_KANTOR'; }
                $obinsert->$key = $value;
            endforeach;
            $obinsert->save();
        endforeach;
        
    }
    
    public function spjmGetXml()
    {
        $xml = simplexml_load_file(url('xml/GetSpjm26012017.xml'));
        
        foreach($xml->children() as $child) {
            $header = array();
            $kms = [];
            $dok = [];
            $cont = [];
            foreach($child as $key => $value) {
                if($key == 'header' || $key == 'HEADER'){
                    $header[] = $value;
                }else{
                    foreach ($value as $key => $value):
                        if($key == 'kms' || $key == 'KMS'):
                            $kms[] = $value;
                        elseif($key == 'dok' || $key == 'DOC'):
                            $dok[] = $value;
                        elseif($key == 'cont' || $key == 'CONT'):
                            $cont[] = $value;
                        endif;
                    endforeach;
                }
            }
            
            if(count($header) > 0){
                // INSERT DATA
                $spjm = new \App\Models\TpsSpjm;
                foreach ($header[0] as $key=>$value):
                    if($key == 'tgl_pib' || $key == 'tgl_bc11'){
                        $split_val = explode('/', $value);
                        $value = $split_val[2].'-'.$split_val[1].'-'.$split_val[0];
                    }
                    $spjm->$key = $value;
                endforeach;  
                $spjm->TGL_UPLOAD = date('Y-m-d');
                $spjm->JAM_UPLOAD = date('H:i:s');
                
                // CHECK DATA
//                $check = \App\Models\TpsSpjm::where('CAR', $spjm->car)->count();
//                if($check > 0) { continue; }

                $spjm->save();   

                $spjm_id = $spjm->TPS_SPJMXML_PK;

                if(count($kms) > 0){
                    $datakms = array();
                    foreach ($kms[0] as $key=>$value):
                        $datakms[$key] = $value;
                    endforeach;
                    $datakms['TPS_SPJMXML_FK'] = $spjm_id;
                    \DB::table('tps_spjmkmsxml')->insert($datakms);
                }
                if(count($dok) > 0){
                    $datadok = array();
                    foreach ($dok[0] as $key=>$value):
                        $datadok[$key] = $value;
                    endforeach;
                    $datadok['TPS_SPJMXML_FK'] = $spjm_id;
                    \DB::table('tps_spjmdokxml')->insert($datadok);
                }
                if(count($cont) > 0){
                    $datacont = array();
                    foreach ($cont[0] as $key=>$value):
                        $datacont[$key] = $value;
                    endforeach;
                    $datacont['TPS_SPJMXML_FK'] = $spjm_id;
                    \DB::table('tps_spjmcontxml')->insert($datacont);
                }
            }
        }
        
        return back()->with('success', 'Success.');
    }
    
    public function sppbPibGetXml()
    {     
        $xml = simplexml_load_file(url('xml/GetImpPermit20161107071906.xml'));

        foreach ($xml->children() as $data):  
            foreach ($data as $key=>$value):
                if($key == 'HEADER'){           
                    $sppb = new \App\Models\TpsSppbPib;
                    foreach ($value as $keyh=>$valueh):
                        if($keyh == 'TG_BL_AWB'){ $keyh='TGL_BL_AWB'; }
                        elseif($keyh == 'TG_MASTER_BL_AWB'){ $keyh='TGL_MASTER_BL_AWB'; }
                        $sppb->$keyh = $valueh;
                    endforeach;
                    $sppb->save();
                    $sppb_id = $sppb->TPS_SPPBXML_PK;
                }elseif($key == 'DETIL'){
                    foreach ($value as $key1=>$value1):
                        if($key1 == 'KMS'){
                            $kms = new \App\Models\TpsSppbPibKms;
                            foreach ($value1 as $keyk=>$valuek):
                                $kms->$keyk = $valuek;
                            endforeach;
                            $kms->TPS_SPPBXML_FK = $sppb_id;
                            $kms->save();
                        }elseif($key1 == 'CONT'){
                            $cont = new \App\Models\TpsSppbPibCont;
                            foreach ($value1 as $keyc=>$valuec):
                                $cont->$keyc = $valuec;
                            endforeach;
                            $cont->TPS_SPPBXML_FK = $sppb_id;
                            $cont->save();
                        }
                    endforeach;  
                }
            endforeach;
        endforeach;
        
    }
    
    public function sppbBcGetXml()
    {     
        $xml = simplexml_load_file(url('xml/GetImpPermitBC2320161111091704.xml'));

        foreach ($xml->children() as $data):  
            foreach ($data as $key=>$value):
                if($key == 'HEADER'){           
                    $sppb = new \App\Models\TpsSppbBc;
                    foreach ($value as $keyh=>$valueh):
                        if($keyh == 'TG_BL_AWB'){ $keyh='TGL_BL_AWB'; }
                        elseif($keyh == 'TG_MASTER_BL_AWB'){ $keyh='TGL_MASTER_BL_AWB'; }
                        elseif($keyh == 'BRUTTO'){ $keyh='BRUTO'; }
                        $sppb->$keyh = $valueh;
                    endforeach;
                    $sppb->save();
                    $sppb_id = $sppb->TPS_SPPBXML_PK;
                }elseif($key == 'DETIL'){
                    foreach ($value as $key1=>$value1):
                        if($key1 == 'KMS'){
                            $kms = new \App\Models\TpsSppbBcKms;
                            foreach ($value1 as $keyk=>$valuek):
                                $kms->$keyk = $valuek;
                            endforeach;
                            $kms->TPS_SPPBXML_FK = $sppb_id;
                            $kms->save();
                        }elseif($key1 == 'CONT'){
                            $cont = new \App\Models\TpsSppbBcCont;
                            foreach ($value1 as $keyc=>$valuec):
                                $cont->$keyc = $valuec;
                            endforeach;
                            $cont->TPS_SPPBXML_FK = $sppb_id;
                            $cont->save();
                        }
                    endforeach;  
                }
            endforeach;
        endforeach;
        
    }
    
    public function rejectGetXml()
    {
        
    }
    
    public function responPlpCreateJoborder(Request $request, $id)
    {
        
        $plpId = $request->id; 
        $plp = \App\Models\TpsResponPlp::where('tps_responplptujuanxml_pk', $plpId)->first();
        
        if($plp){
            
            $plpDetail = \App\Models\TpsResponPlpDetail::where('tps_responplptujuanxml_fk', $plpId)->groupBy('NO_POS_BC11')->get();

            foreach($plpDetail as $detail):
                
                $nopos = substr($detail->NO_POS_BC11,0,4);
//                $checkJoborder = \App\Models\Jobordercy::where(array('TNO_PLP' => $plp->NO_PLP, 'TTGL_PLP' => $plp->TGL_PLP))->count();
//                
//                if($checkJoborder == 0){
                
                    $data = array();
                    $spk_last_id = \App\Models\Jobordercy::select('TJOBORDER_PK as id')->orderBy('TJOBORDER_PK', 'DESC')->first(); 
                    $regID = str_pad(intval((isset($spk_last_id->id) ? $spk_last_id->id : 0)+1), 4, '0', STR_PAD_LEFT);

                    $data['NOJOBORDER'] = 'WIRAL'.$regID.'/'.date('y');
                    $data['NO_BC11'] = $plp->NO_BC11;
                    $data['NO_POS_BC11'] = $nopos;
                    $data['TNO_PLP'] = $plp->NO_PLP;
                    $data['GUDANG_TUJUAN'] = $plp->GUDANG_TUJUAN;
                    $data['KODE_GUDANG'] = $plp->KD_TPS;
                    $data['VESSEL'] = $plp->NM_ANGKUT;
                    $data['CALLSIGN'] = $plp->CALL_SIGN;
                    $data['VOY'] = $plp->NO_VOY_FLIGHT;
                    $data['PARTY'] = $detail->UK_CONT;
                    $data['UID'] = \Auth::getUser()->name;
                    $data['TGLENTRY'] = date('Y-m-d');
                    $data['TGLMBL'] = '0000-00-00';
                    $data['ETA'] = (!empty($plp->TGL_TIBA)) ? date('Y-m-d', strtotime($plp->TGL_TIBA)) : '0000-00-00';
                    $data['ETD'] = '0000-00-00';
                    $data['TGL_BC11'] = (!empty($plp->TGL_BC11)) ? date('Y-m-d', strtotime($plp->TGL_BC11)) : '0000-00-00';
                    $data['TTGL_PLP'] = (!empty($plp->TGL_PLP)) ? date('Y-m-d', strtotime($plp->TGL_PLP)) : '0000-00-00';

                    $namalokasisandar = \App\Models\Lokasisandar::select('TLOKASISANDAR_PK','NAMALOKASISANDAR','KD_TPS_ASAL')->where('KD_TPS_ASAL',$plp->KD_TPS_ASAL)->first();
                    if($namalokasisandar){
                        $data['TLOKASISANDAR_FK'] = $namalokasisandar->TLOKASISANDAR_PK;
                        $data['NAMALOKASISANDAR'] = $namalokasisandar->NAMALOKASISANDAR;
                        $data['KD_TPS_ASAL'] = $namalokasisandar->KD_TPS_ASAL;

                        $data['TCONSOLIDATOR_FK'] = $namalokasisandar->TLOKASISANDAR_PK;
                        $data['NAMACONSOLIDATOR'] = $namalokasisandar->NAMALOKASISANDAR;
                    }

                    $namaconsignee = \App\Models\Perusahaan::select('TPERUSAHAAN_PK','NAMAPERUSAHAAN','NPWP')->where('NAMAPERUSAHAAN',$detail->CONSIGNEE)->first();
                    if($namaconsignee){
                        $data['TCONSIGNEE_FK'] = $namaconsignee->TPERUSAHAAN_PK;
                        $data['CONSIGNEE'] = $namaconsignee->NAMAPERUSAHAAN;
                        $data['ID_CONSIGNEE'] = str_replace(array('.','-'),array('',''),$namaconsignee->NPWP);
                    }

                    $insert_id = \App\Models\Jobordercy::insertGetId($data);
                    if($insert_id){
                        $plpDetailByPos = \App\Models\TpsResponPlpDetail::where(array('tps_responplptujuanxml_fk' => $plpId, 'NO_POS_BC11' => $detail->NO_POS_BC11))->get();
                        foreach($plpDetailByPos as $detailByPost):
                            
//                            $checkCont = \App\Models\Containercy::where('NOCONTAINER', $detailByPost->NO_CONT)->count();
//                            if($checkCont == 0){
                                // COPY JOBORDER
                                $joborder = \App\Models\Jobordercy::findOrFail($insert_id);

                                $data = array();

                                $data['NO_BL_AWB'] = $detailByPost->NO_BL_AWB;
                                $data['TGL_BL_AWB'] = $detailByPost->TGL_BL_AWB;
                                $data['NOCONTAINER'] = $detailByPost->NO_CONT;
                                $data['SIZE'] = $detailByPost->UK_CONT;
                                $data['TEUS'] = $detailByPost->UK_CONT / 20;
                                $data['TJOBORDER_FK'] = $joborder->TJOBORDER_PK;
                                $data['NoJob'] = $joborder->NOJOBORDER;
                                $data['NOMBL'] = $joborder->NOMBL;
                                $data['TGLMBL'] = $joborder->TGLMBL;
                                $data['NO_BC11'] = $joborder->NO_BC11;
                                $data['TGL_BC11'] = $joborder->TGL_BC11;
                                $data['NO_POS_BC11'] = $joborder->NO_POS_BC11;
                                $data['NO_PLP'] = $joborder->TNO_PLP;
                                $data['TGL_PLP'] = $joborder->TTGL_PLP;
                                $data['TCONSOLIDATOR_FK'] = $joborder->TCONSOLIDATOR_FK;
                                $data['NAMACONSOLIDATOR'] = $joborder->NAMACONSOLIDATOR;
                                $data['TCONSIGNEE_FK'] = $joborder->TCONSIGNEE_FK;
                                $data['CONSIGNEE'] = $joborder->CONSIGNEE;
                                $data['ID_CONSIGNEE'] = $joborder->ID_CONSIGNEE;
                        //        $data['TLOKASISANDAR_FK'] = $joborder->TLOKASISANDAR_FK;
                                $data['ETA'] = $joborder->ETA;
                                $data['ETD'] = $joborder->ETD;
                                $data['VESSEL'] = $joborder->VESSEL;
                                $data['VOY'] = $joborder->VOY;
                        //        $data['TPELABUHAN_FK'] = $joborder->TPELABUHAN_FK;
                        //        $data['NAMAPELABUHAN'] = $joborder->NAMAPELABUHAN;
                                $data['PEL_MUAT'] = $joborder->PEL_MUAT;
                                $data['PEL_BONGKAR'] = $joborder->PEL_BONGKAR;
                                $data['PEL_TRANSIT'] = $joborder->PEL_TRANSIT;
                                $data['KD_TPS_ASAL'] = $joborder->KD_TPS_ASAL;
                                $data['GUDANG_TUJUAN'] = $joborder->GUDANG_TUJUAN;
                                $data['CALLSIGN'] = $joborder->CALLSIGN;
                                $data['UID'] = \Auth::getUser()->name;

                                $container_insert_id = \App\Models\Containercy::insertGetId($data);
//                            }
                        endforeach;
                    }
                
//                }else{
//                    return json_encode(array('success' => true, 'message' => 'No. PLP '.$plp->NO_PLP.', sudah pernah dibuat.'));
//                }

            endforeach;
            
            return json_encode(array('success' => true, 'message' => 'No. PLP '.$plp->NO_PLP.', Register berhasih dibuat.'));
        }
        
        return json_encode(array('success' => true, 'message' => 'Something went wrong, please try again later.'));
    }
    
    public function responPlpCreateJoborderLcl(Request $request, $id)
    {
        
        $plpId = $id; 
        $plp = \App\Models\TpsResponPlp::where('tps_responplptujuanxml_pk', $plpId)->first();
        
        if($plp){
            
//            $plpDetail = \App\Models\TpsResponPlpDetail::where('tps_responplptujuanxml_fk', $plpId)->groupBy('NO_POS_BC11')->get();
//
//            foreach($plpDetail as $detail):
//                
//                $nopos = substr($detail->NO_POS_BC11,0,4);
                $checkJoborder = \App\Models\Joborder::where(array('TNO_PLP' => $plp->NO_PLP, 'TTGL_PLP' => $plp->TGL_PLP))->count();
////                
                if($checkJoborder == 0){
                
                    $data = array();
                    $spk_last_id = \App\Models\Joborder::select('TJOBORDER_PK as id')->orderBy('TJOBORDER_PK', 'DESC')->first(); 
                    $regID = str_pad(intval((isset($spk_last_id->id) ? $spk_last_id->id : 0)+1), 4, '0', STR_PAD_LEFT);
                    
                    $data['NOMBL'] = $request->NOMBL;
                    $data['TGL_MASTER_BL'] = (!empty($request->TGL_MASTER_BL) ? date('Y-m-d', strtotime($request->TGL_MASTER_BL)) : null);
                    $data['NOJOBORDER'] = 'WIRAG'.$regID.'/'.date('y');
                    $data['TNO_BC11'] = $plp->NO_BC11;
                    $data['TTGL_BC11'] = (!empty($plp->TGL_BC11)) ? date('Y-m-d', strtotime($plp->TGL_BC11)) : null;
//                    $data['NO_POS_BC11'] = $nopos;
                    $data['TNO_PLP'] = $plp->NO_PLP;
                    $data['TTGL_PLP'] = (!empty($plp->TGL_PLP)) ? date('Y-m-d', strtotime($plp->TGL_PLP)) : null;
                    $data['GUDANG_TUJUAN'] = $plp->GUDANG_TUJUAN;
                    $data['KODE_GUDANG'] = $plp->KD_TPS;
                    $data['VESSEL'] = $plp->NM_ANGKUT;
                    $data['CALLSIGN'] = $plp->CALL_SIGN;
                    $data['VOY'] = $plp->NO_VOY_FLIGHT;
                    $data['UID'] = \Auth::getUser()->name;
                    $data['TGLENTRY'] = date('Y-m-d');
                    $data['ETA'] = (!empty($plp->TGL_TIBA)) ? date('Y-m-d', strtotime($plp->TGL_TIBA)) : null;
                    $data['ETD'] = null;
                    
                    $namalokasisandar = \App\Models\Lokasisandar::select('TLOKASISANDAR_PK','NAMALOKASISANDAR','KD_TPS_ASAL')->where('KD_TPS_ASAL',$plp->KD_TPS_ASAL)->first();
                    if($namalokasisandar){
                        $data['TLOKASISANDAR_FK'] = $namalokasisandar->TLOKASISANDAR_PK;
                        $data['NAMALOKASISANDAR'] = $namalokasisandar->NAMALOKASISANDAR;
                        $data['KD_TPS_ASAL'] = $namalokasisandar->KD_TPS_ASAL;
                    }

                    $insert_id = \App\Models\Joborder::insertGetId($data);
                    if($insert_id){
                        $joborder = \App\Models\Joborder::findOrFail($insert_id);
            
                        $data = array();

                        $data['TJOBORDER_FK'] = $joborder->TJOBORDER_PK;
                        $data['NoJob'] = $joborder->NOJOBORDER;
                        $data['NO_BC11'] = $joborder->TNO_BC11;
                        $data['TGL_BC11'] = $joborder->TTGL_BC11;
                        $data['NO_PLP'] = $joborder->TNO_PLP;
                        $data['TGL_PLP'] = $joborder->TTGL_PLP;
//                        $data['TCONSOLIDATOR_FK'] = $joborder->TCONSOLIDATOR_FK;
//                        $data['NAMACONSOLIDATOR'] = $joborder->NAMACONSOLIDATOR;
                        $data['TLOKASISANDAR_FK'] = $joborder->TLOKASISANDAR_FK;
                        $data['ETA'] = $joborder->ETA;
                        $data['ETD'] = $joborder->ETD;
                        $data['VESSEL'] = $joborder->VESSEL;
                        $data['VOY'] = $joborder->VOY;
//                        $data['TPELABUHAN_FK'] = $joborder->TPELABUHAN_FK;
//                        $data['NAMAPELABUHAN'] = $joborder->NAMAPELABUHAN;
//                        $data['PEL_MUAT'] = $joborder->PEL_MUAT;
//                        $data['PEL_BONGKAR'] = $joborder->PEL_BONGKAR;
//                        $data['PEL_TRANSIT'] = $joborder->PEL_TRANSIT;
                        $data['NOMBL'] = $joborder->NOMBL;
                        $data['TGL_MASTER_BL'] = $joborder->TGL_MASTER_BL;
                        $data['KD_TPS_ASAL'] = $joborder->KD_TPS_ASAL;
                        $data['KD_TPS_TUJUAN'] = $joborder->GUDANG_TUJUAN;
                        $data['CALL_SIGN'] = $joborder->CALLSIGN;

                        $container_insert_id = \App\Models\Container::insertGetId($data);
                        
                        return back()->with('success', 'No. PLP '.$plp->NO_PLP.', Register berhasih dibuat.');
                    }
 
                }else{
                    
                    return back()->with('error', 'No. PLP '.$plp->NO_PLP.', sudah ada di data Register.');
                }
        }
        
        return back()->with('error', 'Something went wrong, please try again later.');
    }
    
    public function responPlpCetakPermohonan($id)
    {
        $data['respon'] = \App\Models\TpsResponPlp::find($id);
        $data['details'] = \App\Models\TpsResponPlpDetail::where('tps_responplptujuanxml_fk', $id)->groupby('NO_CONT')->get();
        
        return view('print.respon-plp-permohonan', $data);
    }
    
    public function getCountPlp(Request $request)
    {
        if(isset($request->startdate) && isset($request->enddate)){
        
            if($request->by == 'TGL_UPLOAD') {
                $start_date = date('Y-m-d 00:00:00',strtotime($request->startdate));
                $end_date = date('Y-m-d 23:59:59',strtotime($request->enddate));
            }else{
                $start_date = date('Ymd',strtotime($request->startdate));
                $end_date = date('Ymd',strtotime($request->enddate));      
            }
            
            $headers = \DB::table('tps_responplptujuanxml')
                    ->select('tps_responplptujuanxml_pk')
                    ->where($request->by, '>=', $start_date)
                    ->where($request->by, '<=', $end_date)
                    ->get();
            
            $l20 = 0;$l40 = 0;$f20 = 0;$f40 = 0;$f45 = 0;$u = 0;
            
            foreach ($headers as $header):
                $details = \DB::table('tps_responplptujuandetailxml')
                    ->select('JNS_CONT','UK_CONT')
                    ->where('tps_responplptujuanxml_fk', $header->tps_responplptujuanxml_pk)
//                    ->where('JNS_CONT','!=','')
                    ->groupBy('NO_CONT')
                    ->get();
                
                if(count($details) > 0){
//                    $details = \DB::table('tps_responplptujuandetailxml')
//                    ->where('tps_responplptujuanxml_fk', $header->tps_responplptujuanxml_pk)
//                    ->get();
                    foreach ($details as $detail):
                        if($detail->JNS_CONT == 'L' && $detail->UK_CONT == 20){
                            $l20 ++;
//                            break;
                        }elseif($detail->JNS_CONT == 'L' && $detail->UK_CONT == 40){
                            $l40 ++;
//                            break;
                        }elseif($detail->JNS_CONT == 'F' && $detail->UK_CONT == 20){
                            $f20 ++;
//                            break;
                        }elseif($detail->JNS_CONT == 'F' && $detail->UK_CONT == 40){
                            $f40 ++;
//                            break;
                        }elseif($detail->JNS_CONT == 'F' && $detail->UK_CONT == 45){
                            $f45 ++;
//                            break;
                        }else{
                            $u ++;
                        }
                    endforeach;
                }
//                else{
//                    $u ++;
//                }
            endforeach;
            
            return json_encode(array(
                's' => true,
                'f' => array('k' => $f20, 's' => $f40, 'b' => $f45),
                'l' => array('k' => $l20, 's' => $l40),
                'u' => $u
            ));
            
        }
        
        return json_encode(array(
            'm' => 'Something wrong, please try again.',
            's' => false
        ));
    }
}
