<?php
namespace App\Models;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;
use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract;
 
class TablesRepository extends EloquentRepositoryAbstract {
 
    public function __construct(Model $Model, $request = null)
    {
        $Columns = array('*');
        
        if($Model->getMorphClass() == 'App\Models\Consolidator'){
            
            $Model = \DB::table('tconsolidator');
//                    ->leftjoin('tconsolidator_tarif', 'tconsolidator.TCONSOLIDATOR_PK', '=', 'tconsolidator_tarif.TCONSOLIDATOR_FK');
            
        }elseif($Model->getMorphClass() == 'App\User'){
            
            $Model = \DB::table('users')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id');
            
            $Columns = array('users.*','roles.name as roles.name');
            
        }elseif($Model->getMorphClass() == 'App\Models\Container'){
            if(isset($request['jobid'])){
                
                $Model = \DB::table('tcontainer')
//                        ->leftjoin('tdepomty', 'tcontainer.TUJUAN_MTY', '=', 'tdepomty.TDEPOMTY_PK')
                        ->where('TJOBORDER_FK', $request['jobid']);
                
            }elseif(isset($request['startdate']) || isset($request['enddate'])){
                
                $Model = \DB::table('tcontainer')
//                        ->leftjoin('tdepomty', 'tcontainer.TUJUAN_MTY', '=', 'tdepomty.TDEPOMTY_PK')
                        ->where('TGLENTRY', '>=',date('Y-m-d 00:00:00',strtotime($request['startdate'])))
                        ->where('TGLENTRY', '<=',date('Y-m-d 23:59:59',strtotime($request['enddate'])));
                
            }elseif(isset($request['module'])){
                
                switch ($request['module']) {
                    case 'gatein':
                        $Model = \DB::table('tcontainer')
//                            ->leftjoin('tdepomty', 'tcontainer.TUJUAN_MTY', '=', 'tdepomty.TDEPOMTY_PK')
                            ->whereNotNull('NO_BC11')
                            ->whereNotNull('TGL_BC11')
                            ->whereNotNull('NO_PLP')
                            ->whereNotNull('TGL_PLP')
                            ->whereNull('TGLKELUAR');
                    break;
                    case 'stripping':
                        $Model = \DB::table('tcontainer')
//                            ->leftjoin('tdepomty', 'tcontainer.TUJUAN_MTY', '=', 'tdepomty.TDEPOMTY_PK')
                            ->whereNotNull('TGLMASUK')
                            ->whereNotNull('JAMMASUK')
                            ->whereNull('TGLKELUAR');
                    break;
                    case 'buangmty':
                        $Model = \DB::table('tcontainer')
//                            ->leftjoin('tdepomty', 'tcontainer.TUJUAN_MTY', '=', 'tdepomty.TDEPOMTY_PK')
                            ->whereNotNull('TGLSTRIPPING')
                            ->whereNotNull('JAMSTRIPPING');
                    break;
                }
                
            }else{
                $Model = \DB::table('tcontainer')
//                        ->leftjoin('tdepomty', 'tcontainer.TUJUAN_MTY', '=', 'tdepomty.TDEPOMTY_PK')
                        ;
            }
            
        }elseif($Model->getMorphClass() == 'App\Models\Containercy'){
            
            if(isset($request['jobid'])){
                
                $Model = \DB::table('tcontainercy')
                        ->where('TJOBORDER_FK', $request['jobid']);
                
            }elseif(isset($request['module'])){
                
                switch ($request['module']) {
                    case 'status_behandle':
                        $Model = \DB::table('tcontainercy')
                            ->whereNotNull('NO_SPJM')
                            ->whereNotNull('TGL_SPJM')
//                            ->where('BEHANDLE', 'Y')
//                            ->whereNull('TGLBEHANDLE')
//                            ->whereIn('status_behandle',array('Ready','Checking'))
                            ->whereNotIn('status_behandle',array('','Finish','Delivery','Selesai Periksa'))
//                            ->whereIn('TGLBEHANDLE',array(NULL,'0000-00-00',''))
//                            ->where('flag_bc','N')
//                            ->orWhere('alasan_segel', 'IKP / Temuan Lapangan')
                            ->orWhere(function($query)
                            {
                                $query->where("flag_bc","Y")
                                      ->where("alasan_segel","IKP / Temuan Lapangan");
                            })
                            ;
                    break;
                    case 'finish_behandle':
                        $Model = \DB::table('tcontainercy')
                            ->whereNotNull('NO_SPJM')
                            ->whereNotNull('TGL_SPJM')
                            ->whereNotNull('TGLBEHANDLE')
//                            ->where('status_behandle','Finish');
                            ->whereIn('status_behandle', array('Finish','Delivery','Selesai Periksa'));
                    break;
                    case 'behandle':

                    break;
                    case 'fiatmuat':
//                        $Model = \DB::table('tcontainercy');
//                            ->select('tmanifest.*','tperusahaan.NPWP as NPWP_CONSIGNEE')
//                            ->join('tperusahaan', 'tperusahaan.TPERUSAHAAN_PK', '=', 'tmanifest.TCONSIGNEE_FK')
//                            ->whereNotNull('NO_SPJM')
//                            ->whereNotNull('TGL_SPJM');
                    break;
                    case 'suratjalan':
                        $Model = \DB::table('tcontainercy')
                            ->whereNotNull('NO_SPPB')
                            ->whereNotNull('TGL_SPPB');
                    break;
                    case 'release':
//                        $Model = \DB::table('tcontainercy')
//                            ->whereNotNull('TGLSURATJALAN')
//                            ->whereNotNull('JAMSURATJALAN')
//                            ;
                    break;
                    case 'release-invoice':
                        $Model = \DB::table('tcontainercy')
                            ->whereIn('KD_TPS_ASAL', array('NCT1','JICT'))
                            ->whereNotNull('TGLMASUK')
                            ->whereNotNull('JAMMASUK')
//                            ->whereNull('TGLRELEASE')
//                            ->whereNotNull('JAMRELEASE')
                            ;
                    break;
                    case 'hold':
                        if(isset($request['startdate']) || isset($request['enddate'])){
                            $start_date = date('Y-m-d',strtotime($request['startdate']));
                            $end_date = date('Y-m-d',strtotime($request['enddate']));  
                            
                            $Model = \DB::table('tcontainercy')
                                ->select(\DB::raw('*, timestampdiff(DAY, now(), TGLMASUK) as timeSinceUpdate'))
                                ->where('status_bc','HOLD')
                                ->where($request['by'], '>=',$start_date)
                                ->where($request['by'], '<=',$end_date);
                        }else{
                            $Model = \DB::table('tcontainercy')
                                ->select(\DB::raw('*, timestampdiff(DAY, now(), TGLMASUK) as timeSinceUpdate'))
                                ->where('status_bc','HOLD');
                        }
                    break;
                    case 'segel':
                        if(isset($request['startdate']) || isset($request['enddate'])){
                            $start_date = date('Y-m-d',strtotime($request['startdate']));
                            $end_date = date('Y-m-d',strtotime($request['enddate']));  
                            
                            $Model = \DB::table('tcontainercy')
                                ->select(\DB::raw('*, timestampdiff(DAY, now(), TGLMASUK) as timeSinceUpdate'))
//                                ->whereNotNull('TGLMASUK')
                                ->whereNull('TGLRELEASE')
//                                ->orWhere('TGL_DISPATCHE','!=',NULL)
                                ->where(function($query)
                                {
                                    $query->whereNotNull("TGLMASUK")
                                          ->orWhere('TGL_DISPATCHE','!=',NULL);
                                })
                                ->where($request['by'], '>=',$start_date)
                                ->where($request['by'], '<=',$end_date);
                        }else{
                            $Model = \DB::table('tcontainercy')
                                ->select(\DB::raw('*, timestampdiff(DAY, now(), TGLMASUK) as timeSinceUpdate'))
//                                ->whereNotNull('TGLMASUK')
                                ->whereNull('TGLRELEASE')
//                                ->orWhere('TGL_DISPATCHE','!=',NULL)
                                ->where(function($query)
                                {
                                    $query->whereNotNull("TGLMASUK")
                                          ->orWhere('TGL_DISPATCHE','!=',NULL);
                                })
                                ;
                        }
                    break;
                    case 'segel_report':
                        $Model = \DB::table('tcontainercy')
                            ->select(\DB::raw('*, timestampdiff(DAY, now(), TGLMASUK) as timeSinceUpdate'))
                            ->whereNotNull('no_unflag_bc')
                            ->whereNotNull('alasan_lepas_segel');
                    break;
                    case 'longstay':
                        if(isset($request['startdate']) || isset($request['enddate'])){
                            $start_date = date('Y-m-d',strtotime($request['startdate']));
                            $end_date = date('Y-m-d',strtotime($request['enddate']));  
                            
                            $Model = \DB::table('tcontainercy')
                                ->select(\DB::raw('*, timestampdiff(DAY, now(), TGLMASUK) as timeSinceUpdate'))
    //                            ->whereRaw('tmanifest.tglmasuk < DATE_SUB(now(), INTERVAL 1 MONTH)')
                                ->whereNotNull('TGLMASUK')
                                ->whereNull('TGLRELEASE')
//                                ->orWhere('tglrelease','0000-00-00')
                                ->where($request['by'], '>=',$start_date)
                                ->where($request['by'], '<=',$end_date);
                        }else{
                            $Model = \DB::table('tcontainercy')
                                ->select(\DB::raw('*, timestampdiff(DAY, now(), TGLMASUK) as timeSinceUpdate'))
    //                            ->whereRaw('tcontainercy.TGLMASUK < DATE_SUB(now(), INTERVAL 1 MONTH)')
                                ->whereNotNull('TGLMASUK')
                                ->whereNull('TGLRELEASE');
//                                ->orWhere('TGLRELEASE','0000-00-00');
                        }
                    break;
                    case 'gatein':
                        $Model = \DB::table('tcontainercy')
                            ->whereNotNull('NO_BC11')
                            ->whereNotNull('TGL_BC11')
                            ->whereNotNull('NO_PLP')
                            ->whereNotNull('TGL_PLP')
                            ->whereNull('TGLRELEASE');
                    break;
                }
                
            }elseif(isset($request['report'])){
                if(isset($request['date'])){
                    if($request['type'] == 'in'){
                $Model = \DB::table('tcontainercy')
                            ->where('TGLMASUK', $request['date']);
                    }elseif($request['type'] == 'out'){
                        $Model = \DB::table('tcontainercy')
                            ->where('TGLRELEASE', $request['date']);
                    } 
                }else{
                    $Model = \DB::table('tcontainercy')
                        ->select(\DB::raw('*, timestampdiff(DAY, now(), TGLMASUK) as timeSinceUpdate'));
                }
            }else{
                
            }
            
        }elseif($Model->getMorphClass() == 'App\Models\Joborder'){
            
            if(isset($request['startdate']) || isset($request['enddate'])){
                
                $Model = \DB::table('tjoborder')->join('tcontainer', 'tjoborder.TJOBORDER_PK', '=', 'tcontainer.TJOBORDER_FK')
                        ->select('tjoborder.*','tcontainer.NOMBL as tcontainer.NOMBL','tcontainer.TGL_MASTER_BL as tcontainer.TGL_MASTER_BL','tjoborder.NAMACONSOLIDATOR as tjoborder.NAMACONSOLIDATOR','tjoborder.VESSEL as tjoborder.VESSEL','tjoborder.VOY as tjoborder.VOY','tcontainer.*')
                        ->where('tcontainer.TGLENTRY', '>=',date('Y-m-d 00:00:00',strtotime($request['startdate'])))
                        ->where('tcontainer.TGLENTRY', '<=',date('Y-m-d 23:59:59',strtotime($request['enddate'])));
                
            }else{
                $Model = \DB::table('tjoborder')
                        ->select('tjoborder.*','tcontainer.NOMBL as tcontainer.NOMBL','tcontainer.TGL_MASTER_BL as tcontainer.TGL_MASTER_BL','tjoborder.NAMACONSOLIDATOR as tjoborder.NAMACONSOLIDATOR','tjoborder.VESSEL as tjoborder.VESSEL','tjoborder.VOY as tjoborder.VOY','tcontainer.*')
                        ->join('tcontainer', 'tjoborder.TJOBORDER_PK', '=', 'tcontainer.TJOBORDER_FK');
            }
            
        }elseif($Model->getMorphClass() == 'App\Models\Jobordercy'){
            
            if(isset($request['jobid'])){
                
                $Model = \DB::table('tjobordercy')->join('tcontainercy', 'tjobordercy.TJOBORDER_PK', '=', 'tcontainercy.TJOBORDER_FK')
                        ->where('TJOBORDER_PK', $request['jobid']);
                
            }elseif(isset($request['startdate']) || isset($request['enddate'])){
                
                $Model = \DB::table('tjobordercy')->join('tcontainercy', 'tjobordercy.TJOBORDER_PK', '=', 'tcontainercy.TJOBORDER_FK')
                        ->select('tjobordercy.*','tcontainercy.*','tcontainercy.CONSIGNEE as tcontainercy.CONSIGNEE')
                        ->where('tcontainercy.TGLENTRY', '>=',date('Y-m-d 00:00:00',strtotime($request['startdate'])))
                        ->where('tcontainercy.TGLENTRY', '<=',date('Y-m-d 23:59:59',strtotime($request['enddate'])));
                
            }else{
                $Model = \DB::table('tjobordercy')
                        ->select('tjobordercy.*','tcontainercy.*','tcontainercy.CONSIGNEE as tcontainercy.CONSIGNEE')
                        ->join('tcontainercy', 'tjobordercy.TJOBORDER_PK', '=', 'tcontainercy.TJOBORDER_FK');
            }
            
        }elseif($Model->getMorphClass() == 'App\Models\Manifest'){
            
            if(isset($request['containerid'])){
                
                $Model = \DB::table('tmanifest')
                        ->where('TCONTAINER_FK', $request['containerid']);

            }elseif(isset($request['module'])){
                
                switch ($request['module']) {
                    case 'status_behandle':
                        $Model = \DB::table('tmanifest')
                            ->whereNotNull('NO_SPJM')
                            ->whereNotNull('TGL_SPJM')
//                            ->where('BEHANDLE', 'Y')
//                            ->whereNull('tglbehandle')
//                            ->whereIn('status_behandle',array('Ready','Checking'))
                            ->whereNotIn('status_behandle',array('','Finish','Delivery','Selesai Periksa'))
//                            ->whereIn('TGLBEHANDLE',array(NULL,'0000-00-00',''))
//                            ->where('flag_bc','N')
//                            ->orWhere('alasan_segel', 'IKP / Temuan Lapangan')
                            ->orWhere(function($query)
                            {
                                $query->where("flag_bc","Y")
                                      ->where("alasan_segel","IKP / Temuan Lapangan");
                            })
                            ;
                    break;
                    case 'finish_behandle':
                        $Model = \DB::table('tmanifest')
                            ->whereNotNull('NO_SPJM')
                            ->whereNotNull('TGL_SPJM')
                            ->whereNotNull('tglbehandle')
//                            ->where('status_behandle','Finish');
                            ->whereIn('status_behandle',array('Finish','Delivery','Selesai Periksa'));
                    break;
                    case 'behandle':
                        
                    break;
                    case 'fiatmuat':
                        $Model = \DB::table('tmanifest')
                            ->select('tmanifest.*','tperusahaan.NPWP as NPWP_CONSIGNEE')
                            ->join('tperusahaan', 'tperusahaan.TPERUSAHAAN_PK', '=', 'tmanifest.TCONSIGNEE_FK');
//                            ->whereNotNull('tmanifest.NO_SPJM')
//                            ->whereNotNull('tmanifest.TGL_SPJM');
                    break;
                    case 'suratjalan':
                        $Model = \DB::table('tmanifest')
                            ->whereNotNull('NO_SPPB')
                            ->whereNotNull('TGL_SPPB');
                    break;
                    case 'release':
                        $Model = \DB::table('tmanifest')
                            ->whereNotNull('tglmasuk')
                            ->whereNotNull('tglstripping');
//                            ->select('tmanifest.*','tperusahaan.NPWP as NPWP_CONSIGNEE')
//                            ->join('tperusahaan', 'tperusahaan.TPERUSAHAAN_PK', '=', 'tmanifest.TCONSIGNEE_FK');
//                        $Model = \DB::table('tmanifest')
//                            ->whereNotNull('TGLSURATJALAN')
//                            ->whereNotNull('JAMSURATJALAN');
//                            ->where('VALIDASI', 'Y');
                    break;
                    case 'hold':
                        if(isset($request['startdate']) || isset($request['enddate'])){
                            $start_date = date('Y-m-d',strtotime($request['startdate']));
                            $end_date = date('Y-m-d',strtotime($request['enddate']));  
                            
                            $Model = \DB::table('tmanifest')
                            ->select(\DB::raw('*, timestampdiff(DAY, now(), tglmasuk) as timeSinceUpdate'))
                            ->where('status_bc', 'HOLD')
                            ->where($request['by'], '>=',$start_date)
                            ->where($request['by'], '<=',$end_date);
                        }else{
                            $Model = \DB::table('tmanifest')
                                ->select(\DB::raw('*, timestampdiff(DAY, now(), tglmasuk) as timeSinceUpdate'))
                                ->where('status_bc', 'HOLD');
                        }
                    break;
                    case 'segel':
                        if(isset($request['startdate']) || isset($request['enddate'])){
                            $start_date = date('Y-m-d',strtotime($request['startdate']));
                            $end_date = date('Y-m-d',strtotime($request['enddate']));  
                            
                            $Model = \DB::table('tmanifest')
                            ->select(\DB::raw('*, timestampdiff(DAY, now(), tglmasuk) as timeSinceUpdate'))
                            ->whereNotNull('tglmasuk')
                            ->whereNotNull('tglstripping')
                            ->whereNull('tglrelease')
                            ->where($request['by'], '>=',$start_date)
                            ->where($request['by'], '<=',$end_date);
                        }else{
                            $Model = \DB::table('tmanifest')
                                ->select(\DB::raw('*, timestampdiff(DAY, now(), tglmasuk) as timeSinceUpdate'))
                                ->whereNotNull('tglmasuk')
                                ->whereNotNull('tglstripping')
                                ->whereNull('tglrelease');
                        }
                    break;
                    case 'segel_report':
                        $Model = \DB::table('tmanifest')
                            ->select(\DB::raw('*, timestampdiff(DAY, now(), tglmasuk) as timeSinceUpdate'))
                            ->whereNotNull('no_unflag_bc')
                            ->whereNotNull('alasan_lepas_segel');
                    break;
                    case 'longstay':
                        if(isset($request['startdate']) || isset($request['enddate'])){
                            $start_date = date('Y-m-d',strtotime($request['startdate']));
                            $end_date = date('Y-m-d',strtotime($request['enddate']));  
                            
                            $Model = \DB::table('tmanifest')
                            ->select(\DB::raw('*, timestampdiff(DAY, now(), tglmasuk) as timeSinceUpdate'))
//                            ->whereRaw('tmanifest.tglmasuk < DATE_SUB(now(), INTERVAL 1 MONTH)')
                            ->whereNotNull('tglmasuk')
                            ->whereNotNull('tglstripping')
                            ->whereNull('tglrelease')
//                            ->orWhere('tglrelease','0000-00-00')
                            ->where($request['by'], '>=',$start_date)
                            ->where($request['by'], '<=',$end_date);
                        }else{
                            $Model = \DB::table('tmanifest')
                                ->select(\DB::raw('*, timestampdiff(DAY, now(), tglmasuk) as timeSinceUpdate'))
    //                            ->whereRaw('tmanifest.tglmasuk < DATE_SUB(now(), INTERVAL 1 MONTH)')
                                ->whereNotNull('tglmasuk')
                                ->whereNotNull('tglstripping')
                                ->whereNull('tglrelease');
    //                            ->orWhere('tglrelease','0000-00-00')
                        }
                    break;
                    case 'release-invoice':
                        $Model = \DB::table('tmanifest')
//                            ->select('tmanifest.*','tperusahaan.NPWP as NPWP_CONSIGNEE')
//                            ->join('tperusahaan', 'tperusahaan.TPERUSAHAAN_PK', '=', 'tmanifest.TCONSIGNEE_FK');
//                        $Model = \DB::table('tmanifest')
                            ->whereNotNull('tglmasuk')
                            ->whereNotNull('tglstripping')
//                            ->whereNotNull('tglrelease')
//                            ->whereNotNull('jamrelease')
                            ;
                    break;
                }
                
            }if(isset($request['startdate']) || isset($request['enddate'])){
                
                $start_date = date('Y-m-d',strtotime($request['startdate']));
                $end_date = date('Y-m-d',strtotime($request['enddate']));      
                
                $Model = \DB::table('tmanifest')
                        ->where($request['by'], '>=',$start_date)
                        ->where($request['by'], '<=',$end_date);
                
            }elseif(isset($request['report'])){
                if(isset($request['date'])){
                    if($request['type'] == 'in'){
                $Model = \DB::table('tmanifest')
                            ->where('tglstripping', $request['date']);
                    }elseif($request['type'] == 'out'){
                        $Model = \DB::table('tmanifest')
                            ->where('tglrelease', $request['date']);
                    } 
            }else{
                    $Model = \DB::table('tmanifest')
    //                        ->leftjoin('billing_invoice', 'billing_invoice.manifest_id','=','tmanifest.TMANIFEST_PK')
                            ->select(\DB::raw('*, timestampdiff(DAY, now(), tglmasuk) as timeSinceUpdate'))
                            ->whereNotNull('tglmasuk')
                            ->whereNotNull('tglstripping');   
                }
            }else{
                
            }
            
        }
        
        $this->Database = $Model;        
        $this->visibleColumns = $Columns; 
        $this->orderBy = array(array('id', 'asc'), array('name'));
    }
}