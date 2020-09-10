<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Manifest as DBManifest;
use App\Models\Container as DBContainer;
use App\Models\Perusahaan as DBPerusahaan;
use App\Models\Packing as DBPacking;

class ManifestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( !$this->access->can('show.lcl.manifest.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index LCL Manifest', 'slug' => 'show.lcl.manifest.index', 'description' => ''));
        
        $data['page_title'] = "LCL Manifest";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'LCL Manifest'
            ]
        ];        
        
        return view('import.lcl.index-manifest')->with($data);
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
        $data = $request->json()->all(); 
        unset($data['id'], $data['delete_photo'], $data['_token'], $data['undefined']);
        
        $container = DBContainer::find($data['TCONTAINER_FK']);  
        
        
        $num = 0; 
//        $manifestID = DBManifest::select('NOTALLY')->where('TJOBORDER_FK',$container->TJOBORDER_FK)->count();
        $manifestID = DBManifest::select('NOTALLY')->where('TJOBORDER_FK',$container->TJOBORDER_FK)->orderBy('TMANIFEST_PK', 'DESC')->first();
        if($manifestID){
            $tally = explode('.', $manifestID->NOTALLY);
            $num = intval($tally[1]);    
        }
//        $regID = str_pad(intval(($manifestID > 0 ? $manifestID : 0)+1), 3, '0', STR_PAD_LEFT);
        $regID = str_pad(intval(($num > 0 ? $num : 0)+1), 3, '0', STR_PAD_LEFT);
 
        $data['NOTALLY'] = $container->NoJob.'.'.$regID; 
        $data['TJOBORDER_FK'] = $container->TJOBORDER_FK;
        $packing = DBPacking::find($data['TPACKING_FK']);
        if($packing){
            $data['KODE_KEMAS'] = $packing->KODEPACKING;
            $data['NAMAPACKING'] = $packing->NAMAPACKING;
        }
        $data['NOJOBORDER'] = $container->NoJob;
        $data['NOCONTAINER'] = $container->NOCONTAINER;
        $data['TCONSOLIDATOR_FK'] = $container->TCONSOLIDATOR_FK;
        $data['NAMACONSOLIDATOR'] = $container->NAMACONSOLIDATOR;
        $data['TLOKASISANDAR_FK'] = $container->TLOKASISANDAR_FK;
        $data['KD_TPS_ASAL'] = $container->KD_TPS_ASAL;
        $data['KD_TPS_TUJUAN'] = $container->KD_TPS_TUJUAN;
        $data['SIZE'] = $container->SIZE;
        $data['ETA'] = $container->ETA;
        $data['ETD'] = $container->ETD;
        $data['VESSEL'] = $container->VESSEL;
        $data['VOY'] = $container->VOY;
        $data['CALL_SIGN'] = $container->CALL_SIGN;
        $data['TPELABUHAN_FK'] = $container->TPELABUHAN_FK;     
        $data['NAMAPELABUHAN'] = $container->NAMAPELABUHAN;
        $data['PEL_MUAT'] = $container->PEL_MUAT;
        $data['PEL_BONGKAR'] = $container->PEL_BONGKAR;
        $data['PEL_TRANSIT'] = $container->PEL_TRANSIT;
        $data['NOMBL'] = $container->NOMBL;  
        $data['TGL_MASTER_BL'] = $container->TGL_MASTER_BL;
        $data['LOKASI_GUDANG'] = $container->LOKASI_GUDANG;
        $data['NO_BC11'] = $container->NO_BC11;
        $data['TGL_BC11'] = $container->TGL_BC11;
        $data['NO_PLP'] = $container->NO_PLP;
        $data['TGL_PLP'] = $container->TGL_PLP;
        if($data['TSHIPPER_FK']){
            $data['SHIPPER'] = DBPerusahaan::getNameById($data['TSHIPPER_FK']);
        }
        if($data['TCONSIGNEE_FK']){
            $data['CONSIGNEE'] = DBPerusahaan::getNameById($data['TCONSIGNEE_FK']);
            $data['ID_CONSIGNEE'] = DBPerusahaan::getNpwpById($data['TCONSIGNEE_FK']);
        }        
        $data['VALIDASI'] = 'N';
        if(is_numeric($data['TNOTIFYPARTY_FK'])) {
            $data['NOTIFYPARTY'] = DBPerusahaan::getNameById($data['TNOTIFYPARTY_FK']);
        }else{
            $data['NOTIFYPARTY'] = $data['TNOTIFYPARTY_FK'];
            unset($data['TNOTIFYPARTY_FK']);
        }
        $data['tglmasuk'] = $container->TGLMASUK;
        $data['jammasuk'] = $container->JAMMASUK;
        $data['tglentry'] = date('Y-m-d');
        $data['jamentry'] = date('H:i:s');
        $data['UID'] = \Auth::getUser()->name;
        
        $insert_id = DBManifest::insertGetId($data);
        
        if($insert_id){
            // Update Jumlah BL
            $countbl = DBManifest::where('TCONTAINER_FK', $data['TCONTAINER_FK'])->count();
            
            // Update Meas Wight           
            $sum_weight_manifest = DBManifest::select('WEIGHT')->where('TCONTAINER_FK', $data['TCONTAINER_FK'])->sum('WEIGHT');
            $sum_meas_marnifest = DBManifest::select('MEAS')->where('TCONTAINER_FK', $data['TCONTAINER_FK'])->sum('MEAS');         
            $container->MEAS = $sum_meas_marnifest;
            $container->WEIGHT = $sum_weight_manifest;
            $container->jumlah_bl = $countbl;
            
            if($container->save()){
                
                $sum_weight = DBContainer::select('WEIGHT')->where('TJOBORDER_FK', $container->TJOBORDER_FK)->sum('WEIGHT');
                $sum_meas = DBContainer::select('MEAS')->where('TJOBORDER_FK', $container->TJOBORDER_FK)->sum('MEAS');         
                \App\Models\Joborder::where('TJOBORDER_PK', $container->TJOBORDER_FK)
                        ->update(['MEASUREMENT' => $sum_meas, 'GROSSWEIGHT' => $sum_weight]);
                
            }

            return json_encode(array('success' => true, 'message' => 'Manifest successfully saved!'));
        }
        
        return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
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
        if ( !$this->access->can('show.lcl.manifest.edit') ) {
            return view('errors.no-access');
        }
        
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Edit LCL Manifest', 'slug' => 'show.lcl.manifest.edit', 'description' => ''));
        
        
        $data['page_title'] = "Edit LCL Manifest";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('lcl-manifest-index'),
                'title' => 'LCL Manifest'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
//        $num = 0;
        
//        $manifestID = DBManifest::select('NOTALLY')->orderBy('TMANIFEST_PK', 'DESC')->first();
//        if(count($manifestID) > 0){
//            $tally = explode('.', $manifestID->NOTALLY);
//            $num = intval($tally[1]);    
//        }
//        
//        $regID = str_pad(intval((isset($num) ? $num : 0)+1), 3, '0', STR_PAD_LEFT);
        
        $container = DBContainer::find($id);
        
        $data['container'] = $container;
//        $data['tally_number'] = $container->NoJob.'.'.$regID;
        $data['perusahaans'] = DBPerusahaan::select('TPERUSAHAAN_PK as id', 'NAMAPERUSAHAAN as name')->get();
        $data['packings'] = DBPacking::select('TPACKING_PK as id', 'KODEPACKING as code', 'NAMAPACKING as name')->get();
        $data['locations'] = \DB::table('location')->get();
        
        return view('import.lcl.edit-manifest')->with($data);
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
        $data = $request->json()->all(); 
        $delete_photo = $data['delete_photo'];
        unset($data['id'], $data['delete_photo'], $data['_token'], $data['undefined']);
        
        $container = DBContainer::find($data['TCONTAINER_FK']); 
        $packing = DBPacking::find($data['TPACKING_FK']);
        
        $data['NO_BC11'] = $container->NO_BC11;
        $data['TGL_BC11'] = $container->TGL_BC11;
        $data['NO_PLP'] = $container->NO_PLP;
        $data['TGL_PLP'] = $container->TGL_PLP;
        $data['SIZE'] = $container->SIZE;
        $data['KODE_KEMAS'] = $packing->KODEPACKING;
        $data['NAMAPACKING'] = $packing->NAMAPACKING;  
        $data['SHIPPER'] = DBPerusahaan::getNameById($data['TSHIPPER_FK']);
        $data['CONSIGNEE'] = DBPerusahaan::getNameById($data['TCONSIGNEE_FK']);
        $data['ID_CONSIGNEE'] = DBPerusahaan::getNpwpById($data['TCONSIGNEE_FK']);
        $data['VALIDASI'] = 'N';
        if(is_numeric($data['TNOTIFYPARTY_FK'])) {
            $data['NOTIFYPARTY'] = DBPerusahaan::getNameById($data['TNOTIFYPARTY_FK']);
        }else{
            $data['NOTIFYPARTY'] = $data['TNOTIFYPARTY_FK'];
            unset($data['TNOTIFYPARTY_FK']);
        }
        
        $data['tglmasuk'] = $container->TGLMASUK;
        $data['jammasuk'] = $container->JAMMASUK;
        
//        if(empty($data['perubahan_hbl']) || $data['perubahan_hbl'] == 'N'){
//            $data['alasan_perubahan'] = '';
//        }
        
        if($data['final_qty'] != $data['QUANTITY'] || $data['packing_tally'] != $packing->NAMAPACKING){
            $data['perubahan_hbl'] = 'Y';
        }else{
            $data['perubahan_hbl'] = 'N';
        }
        
        if($delete_photo == 'Y'){
            $data['photo_stripping'] = '';
        }
        
        $locations = \DB::table('location')->whereIn('id', $data['location_id'])->pluck('name');
        
        if($locations){
            $data['location_id'] = implode(',', $data['location_id']);
            $data['location_name'] = implode(',', $locations);
        }
        
//        $location = \DB::table('location')->find($data['location_id']);
//        if($location){
//            $data['location_id'] = $location->id;
//            $data['location_name'] = $location->name;
//        }
        
        $update = DBManifest::where('TMANIFEST_PK', $id)
            ->update($data);
        
        if($update){
            
            // Update Meas Wight           
            $sum_weight_manifest = DBManifest::select('WEIGHT')->where('TCONTAINER_FK', $data['TCONTAINER_FK'])->sum('WEIGHT');
            $sum_meas_marnifest = DBManifest::select('MEAS')->where('TCONTAINER_FK', $data['TCONTAINER_FK'])->sum('MEAS');         
            $container->MEAS = $sum_meas_marnifest;
            $container->WEIGHT = $sum_weight_manifest;
            
            if($container->save()){
                
                $sum_weight = DBContainer::select('WEIGHT')->where('TJOBORDER_FK', $container->TJOBORDER_FK)->sum('WEIGHT');
                $sum_meas = DBContainer::select('MEAS')->where('TJOBORDER_FK', $container->TJOBORDER_FK)->sum('MEAS');         
                \App\Models\Joborder::where('TJOBORDER_PK', $container->TJOBORDER_FK)
                        ->update(['MEASUREMENT' => $sum_meas, 'GROSSWEIGHT' => $sum_weight]);
                
            }
            
            return json_encode(array('success' => true, 'message' => 'Manifest successfully saved!'));
        }
        
        return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      try
      {
            $manifest = DBManifest::findOrFail($id);

          // Update Jumlah BL
            $countbl = DBManifest::where('TCONTAINER_FK', $manifest->TCONTAINER_FK)->count();
            DBContainer::where('TCONTAINER_PK', $manifest->TCONTAINER_FK)
                ->update(['jumlah_bl' => $countbl]);
                  
            DBManifest::destroy($id);
      }
      catch (Exception $e)
      {
        return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
      }
 
      return json_encode(array('success' => true, 'message' => 'Manifest successfully deleted!'));
    }
    
    public function approve($id)
    {
        
        $manifest = DBManifest::find($id);
        
        if(empty($manifest->tglstripping) || $manifest->tglstripping == '0000-00-00' || $manifest->tglstripping == '01-01-1970'){
            return json_encode(array('success' => false, 'message' => 'HBL ini belum melakukan stripping!'));
        }
        
        $update = DBManifest::where('TMANIFEST_PK', $id)
            ->update(array('VALIDASI'=>'Y'));
        
        if($update){
            $manifest = DBManifest::find($id);
            if($manifest->sor_update == 0){
//                $sor = $this->updateSor('approve', $meas->MEAS);
                $this->updateSorByMeas();
                $manifest->sor_update = 1;
                $manifest->save();
            }

            return json_encode(array('success' => true, 'message' => 'Manifest successfully approved!'));
        }
        
        return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
    }
    
    public function approveAll($container_id)
    {
        
        $container = DBContainer::find($container_id);
        
        if(empty($container->TGLSTRIPPING) || $container->TGLSTRIPPING == '0000-00-00' || $container->TGLSTRIPPING == '01-01-1970'){
            return json_encode(array('success' => false, 'message' => 'Kontainer ini belum melakukan stripping!'));
        }

        $update = DBManifest::where('TCONTAINER_FK', $container_id)
            ->update(array('VALIDASI'=>'Y'));
        
        if($update){
            
            $manifest = DBManifest::where('TCONTAINER_FK', $container_id)->get();
            foreach ($manifest as $mfs):
                if($mfs->sor_update == 0){
//                    $sor = $this->updateSor('approve', $mfs->MEAS);
                    $this->updateSorByMeas();
                    DBManifest::where('TMANIFEST_PK', $mfs->TMANIFEST_PK)->update(array('sor_update'=>1));
                }
            endforeach;
            
            $container->status_coari_cargo = 'Ready';
            $container->save();
            
            return json_encode(array('success' => true, 'message' => 'All Manifest successfully approved!'));
        }
        
        return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
    }
    
    public function cetak($id, $type)
    {
        $container = DBContainer::find($id);
        $manifests = DBManifest::where('TCONTAINER_FK', $id)->get();
        
        $data['container'] = $container;
        $data['manifests'] = $manifests;
//        return view('print.tally-sheet', $container);
        
        foreach ($manifests as $mnft):
            $packing = DBPacking::select('KODEPACKING as code')->where('NAMAPACKING',$mnft->NAMAPACKING)->first();
            $packing_tally = DBPacking::select('KODEPACKING as code')->where('NAMAPACKING',$mnft->packing_tally)->first();
            
            $mnft->packing = $packing->code;
            $mnft->packing_tally = $packing_tally->code;
        endforeach;
        
        switch ($type){
            case 'tally':
                return view('print.tally-sheet', $data);
//                $pdf = \PDF::loadView('print.tally-sheet', $data);        
                break;
            case 'log':
                $pdf = \PDF::loadView('print.log-stripping', $data);
                break;
        }
        
        return $pdf->stream(ucfirst($type).'-'.$container->NOCONTAINER.'-'.date('dmy').'.pdf');
    }
    
    public function upload(Request $request)
    {
        $container_id = $request->container_id;
        $container = DBContainer::where('TCONTAINER_PK', $container_id)->first();
        
        // Check data xml
        $check = \App\Models\TpsCoariKmsDetail::where('CONT_ASAL', $container->NOCONTAINER)->count();
        
//        if($check > 0){
//            return json_encode(array('success' => false, 'message' => 'No. Container '.$container->NOCONTAINER.' sudah di upload.'));
//        }
        
        // Reff Number
        $reff_number = $this->getReffNumber();
        
        if($reff_number){
            $coarikms = new \App\Models\TpsCoariKms;
            $coarikms->REF_NUMBER = $reff_number;
            $coarikms->TGL_ENTRY = date('Y-m-d');
            $coarikms->JAM_ENTRY = date('H:i:s');
            $coarikms->UID = \Auth::getUser()->name;
            
            if($coarikms->save()){
                $manifest = DBManifest::where(array('TCONTAINER_FK' => $container->TCONTAINER_PK, 'TJOBORDER_FK' => $container->TJOBORDER_FK, 'VALIDASI' => 'Y'))
                        ->get();
                
                $nourut = 0;
                foreach ($manifest as $data):
                    $coarikmsdetail = new \App\Models\TpsCoariKmsDetail;
                    $coarikmsdetail->TPSCOARIKMSXML_FK = $coarikms->TPSCOARIKMSXML_PK;
                    $coarikmsdetail->REF_NUMBER = $reff_number;
                    $coarikmsdetail->NOTALLY = $data->NOTALLY;
                    $coarikmsdetail->KD_DOK = 5;
                    $coarikmsdetail->KD_TPS = 'WIRA';
                    $coarikmsdetail->NM_ANGKUT = $data->VESSEL;
                    $coarikmsdetail->NO_VOY_FLIGHT = $data->VOY;
                    $coarikmsdetail->CALL_SIGN = $data->CALL_SIGN;
                    $coarikmsdetail->TGL_TIBA = (!empty($data->ETA) ? date('Ymd', strtotime($data->ETA)) : '');
                    $coarikmsdetail->KD_GUDANG = 'WIRA';
                    $coarikmsdetail->NO_BL_AWB = $data->NOHBL;
                    $coarikmsdetail->TGL_BL_AWB = (!empty($data->TGL_HBL) ? date('Ymd', strtotime($data->TGL_HBL)) : '');
                    $coarikmsdetail->NO_MASTER_BL_AWB = $data->NOMBL;
                    $coarikmsdetail->TGL_MASTER_BL_AWB = (!empty($data->TGL_MASTER_BL) ? date('Ymd', strtotime($data->TGL_MASTER_BL)) : '');
                    $coarikmsdetail->ID_CONSIGNEE = str_replace(array('.','-'), array(''),$data->ID_CONSIGNEE);
                    $coarikmsdetail->CONSIGNEE = trim($data->CONSIGNEE);
                    $coarikmsdetail->BRUTO = $data->WEIGHT;
                    $coarikmsdetail->NO_BC11 = $data->NO_BC11;
                    $coarikmsdetail->TGL_BC11 = (!empty($data->TGL_BC11) ? date('Ymd', strtotime($data->TGL_BC11)) : '');
                    $coarikmsdetail->NO_POS_BC11 = $data->NO_POS_BC11;
                    $coarikmsdetail->CONT_ASAL = $data->NOCONTAINER;
                    $coarikmsdetail->SERI_KEMAS = 1;
                    $coarikmsdetail->KD_KEMAS = $data->KODE_KEMAS;
                    $coarikmsdetail->JML_KEMAS = (!empty($data->QUANTITY) ? $data->QUANTITY : 0);
                    $coarikmsdetail->KD_TIMBUN = 'GD';
                    $coarikmsdetail->KD_DOK_INOUT = 3;
                    $coarikmsdetail->NO_DOK_INOUT = (!empty($data->NO_PLP) ? $data->NO_PLP : '');
                    $coarikmsdetail->TGL_DOK_INOUT = (!empty($data->TGL_PLP) ? date('Ymd', strtotime($data->TGL_PLP)) : '');
                    $coarikmsdetail->WK_INOUT = date('Ymd', strtotime($data->tglstripping)).date('His', strtotime($data->jamstripping));
                    $coarikmsdetail->KD_SAR_ANGKUT_INOUT = 1;
                    $coarikmsdetail->NO_POL = $data->NOPOL_MASUK;
                    $coarikmsdetail->PEL_MUAT = $data->PEL_MUAT;
                    $coarikmsdetail->PEL_TRANSIT = $data->PEL_TRANSIT;
                    $coarikmsdetail->PEL_BONGKAR = $data->PEL_BONGKAR;
                    $coarikmsdetail->GUDANG_TUJUAN = 'WIRA';
                    $coarikmsdetail->UID = \Auth::getUser()->name;
                    $coarikmsdetail->RESPONSE = '';
                    $coarikmsdetail->STATUS_TPS = 1;
                    $coarikmsdetail->NOURUT = $nourut;
                    $coarikmsdetail->KODE_KANTOR = '040300';
                    $coarikmsdetail->NO_DAFTAR_PABEAN = '';
                    $coarikmsdetail->TGL_DAFTAR_PABEAN = '';
                    $coarikmsdetail->NO_SEGEL_BC = '';
                    $coarikmsdetail->TGL_SEGEL_BC = '';
                    $coarikmsdetail->NO_IJIN_TPS = '';
                    $coarikmsdetail->TGL_IJIN_TPS = '';
                    $coarikmsdetail->RESPONSE_IPC = '';
                    $coarikmsdetail->STATUS_TPS_IPC = '';
                    $coarikmsdetail->KD_TPS_ASAL = '';
                    $coarikmsdetail->TGL_ENTRY = date('Y-m-d');
                    $coarikmsdetail->JAM_ENTRY = date('H:i:s');
                    
                    if($coarikmsdetail->save()){
                        
                        DBManifest::where('TMANIFEST_PK', $data->TMANIFEST_PK)->update(['REF_NUMBER' => $reff_number]);
                        
                        $nourut++;
                    }
 
                endforeach;
                
                return json_encode(array('insert_id' => $coarikms->TPSCOARIKMSXML_PK, 'ref_number' => $reff_number, 'success' => true, 'message' => 'No. Container '.$container->NOCONTAINER.' berhasil di simpan. Reff Number : '.$reff_number));
                    
            }
            
        } else {
            return json_encode(array('success' => false, 'message' => 'Cannot create Reff Number, please try again later.'));
        }
              
        return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
    }
    
    public function uploadPhoto(Request $request, $ref)
    {
        $picture = array();
        if ($request->hasFile('photos')) {
            $files = $request->file('photos');
            $destinationPath = base_path() . '/public/uploads/photos/manifest';
            $i = 1;
            foreach($files as $file){
//                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                
                $filename = date('dmyHis').'_'.str_slug($request->no_hbl).'_'.$i.'.'.$extension;
                $picture[] = $filename;
                $file->move($destinationPath, $filename);
                $i++;
            }
            // update to Database
            $manifest = DBManifest::find($request->id_hbl);
            $oldJson = json_decode($manifest->$ref);
            $newJson = array_collapse([$oldJson,$picture]);
            $manifest->$ref = json_encode($newJson);
            if($manifest->save()){
                return back()->with('success', 'Photo for Manifest '. $request->no_hbl .' has been uploaded.');
            }else{
                return back()->with('error', 'Photo uploaded, but not save in Database.');
            }
            
        } else {
            return back()->with('error', 'Something wrong!!! Can\'t upload photo, please try again.');
        }
    }
    
    public function getNopos($id)
    {
        $container = DBContainer::find($id);
        $manifests = DBManifest::where('TCONTAINER_FK',$container->TCONTAINER_PK)->get();
        
        $plp = \App\Models\TpsResponPlpDetail::where(array('NO_CONT'=>$container->NOCONTAINER,'UK_CONT'=>$container->SIZE))->get();
        
        if($plp){
            // UPDATE NO.POS
            $i = 0;
            foreach ($manifests as $manifest):
                $plpdetail = \App\Models\TpsResponPlpDetail::where(
                        array(
                            'NO_CONT'=>$manifest->NOCONTAINER,
                            'UK_CONT'=>$manifest->SIZE,
                            'NO_BL_AWB'=>$manifest->NOHBL,
                            'TGL_BL_AWB'=>date('Ymd', strtotime($manifest->TGL_HBL))
                            )
                        )->first();
                if($plpdetail) {
                    // Check Manifest Nopos
                    if($manifest->NO_POS_BC11 == ''){
                        DBManifest::where('TMANIFEST_PK', $manifest->TMANIFEST_PK)->update(['NO_POS_BC11'=>$plpdetail->NO_POS_BC11]);
                        $i++;
                    }
                }
            endforeach;
            
            return json_encode(array('success' => true, 'message' => $i. ' Data No.POS BC11 berhasil di update.'));
            
        }else{
            return json_encode(array('success' => false, 'message' => 'Data Respon PLP tidak ditemukan untuk container ini.'));
        }
              
        return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        
    }
    
}
