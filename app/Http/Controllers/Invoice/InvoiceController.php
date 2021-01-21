<?php

namespace App\Http\Controllers\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceNct;
use App\Models\InvoiceNctPenumpukan;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    
    public function invoiceIndex()
    {
        if ( !$this->access->can('show.invoice.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Invoice";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Invoice'
            ]
        ];        
        
        $data['consolidators'] = \App\Models\Consolidator::select('TCONSOLIDATOR_PK as id','NAMACONSOLIDATOR as name')->get();
        
        return view('invoice.index-invoice')->with($data);
    }
    
    public function releaseIndex()
    {
        if ( !$this->access->can('show.invoice.release.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index Invoice Release', 'slug' => 'show.invoice.release.index', 'description' => ''));
        
        $data['page_title'] = "Invoice Release";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Invoice Release'
            ]
        ];        
        
//        $data['perusahaans'] = DBPerusahaan::select('TPERUSAHAAN_PK as id', 'NAMAPERUSAHAAN as name')->get();
        
        return view('invoice.index-release')->with($data);
    }
    
    public function invoiceEdit($id)
    {
        
        if ( !$this->access->can('edit.invoice.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Edit Invoice";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-index'),
                'title' => 'Invoice'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['invoice'] = \DB::table('invoice_import')->find($id);
        $data['manifest'] = \App\Models\Manifest::find($data['invoice']->manifest_id);
        $data['consignee'] = \App\Models\Perusahaan::find($data['manifest']->TCONSIGNEE_FK);
        $data['tarif'] = \App\Models\InvoiceTarif::where(array('consolidator_id' => $data['manifest']->TCONSOLIDATOR_FK, 'type' => $data['manifest']->INVOICE))->first();
//        $data['tarif'] = \App\Models\ConsolidatorTarif::where('TCONSOLIDATOR_FK', $data['manifest']->TCONSOLIDATOR_FK)->first();
        $total = $data['invoice']->sub_total + $data['invoice']->ppn + $data['invoice']->materai;
        $data['terbilang'] = ucwords($this->terbilang($total))." Rupiah";
        
        return view('invoice.edit-invoice')->with($data);
    }
    
    public function invoiceDestroy($id)
    {
        \DB::table('invoice_import')->where('id', $id)->delete();
        return back()->with('success', 'Invoice has been deleted.'); 
    }
    
    public function invoicePrint($id)
    {
        $data['invoice'] = \DB::table('invoice_import')->find($id);
        $data['manifest'] = \App\Models\Manifest::find($data['invoice']->manifest_id);
        $data['consignee'] = \App\Models\Perusahaan::find($data['manifest']->TCONSIGNEE_FK);
        $data['tarif'] = \App\Models\InvoiceTarif::where(array('consolidator_id' => $data['manifest']->TCONSOLIDATOR_FK, 'type' => $data['manifest']->INVOICE))->first();
//        $data['tarif'] = \App\Models\ConsolidatorTarif::where('TCONSOLIDATOR_FK', $data['manifest']->TCONSOLIDATOR_FK)->first();
        $total = $data['invoice']->sub_total + $data['invoice']->ppn + $data['invoice']->materai;
        $data['terbilang'] = ucwords($this->terbilang($total))." Rupiah";
//        return view('print.bon-muat', $container);
        
//        switch ($type){
//            case 'bon-muat':
//                $pdf = \PDF::loadView('print.bon-muat', $data);        
//                break;
//            case 'surat-jalan':
//                $pdf = \PDF::loadView('print.surat-jalan', $data);
//                break;
//        }
        return view('print.invoice-lcl')->with($data);
        $pdf = \PDF::loadView('print.invoice', $data)->setPaper('a4');
        
        return $pdf->stream($data['invoice']->no_invoice.'-'.date('dmy').'.pdf');
    }
    
    public function invoicePrintRekapAkumulasi(Request $request)
    {
        $consolidator_id = $request->consolidator_id;
        $start = $request->start_date;
        $end = $request->end_date;
        $type = $request->type;
        
        $data['consolidator'] = \App\Models\Consolidator::find($consolidator_id);
        
        $data['invoices'] = \App\Models\Invoice::select('tmanifest.tglrelease', 
                \DB::raw('SUM(invoice_import.sub_total) as sub_total'), 
                \DB::raw('SUM(invoice_import.cbm) as total_cbm'), 
                \DB::raw('SUM(invoice_import.rdm) as total_rdm'),
                \DB::raw('COUNT(invoice_import.id) as total_inv'))               
                ->join('tmanifest','invoice_import.manifest_id','=','tmanifest.TMANIFEST_PK')
                ->where('tmanifest.TCONSOLIDATOR_FK', $consolidator_id)
                ->where('tmanifest.tglrelease','>=',$start)
                ->where('tmanifest.tglrelease','<=',$end)
                ->where('tmanifest.INVOICE', $type)
                ->groupBy('tmanifest.tglrelease')
                ->get();
        
//        return $data['invoices'];
        
        if(count($data['invoices']) > 0):
            
            $sum_total = array();
            foreach ($data['invoices'] as $invoice):
                $sum_total[] = $invoice->sub_total;        
            endforeach;
            
            $data['sub_total'] = array_sum($sum_total);
            if(isset($request->free_ppn)):
                $data['ppn'] = 0;
            else:
                $data['ppn'] = $data['sub_total']*10/100;
            endif;
            
            $data['materai'] = ($data['sub_total'] + $data['ppn'] >= 5000000) ? '10000' : '0';
            $data['total'] = round($data['sub_total'] + $data['ppn'] + $data['materai']);           
            $data['terbilang'] = ucwords($this->terbilang($data['total']))." Rupiah";
            $data['tgl_cetak'] = $request->tgl_cetak;
            $data['tgl_release'] = array('start' => $start, 'end' => $end);
            $data['type'] = $type;
            
            return \View('print.invoice-rekap-akumulasi', $data);
            
            $pdf = \PDF::loadView('print.invoice-rekap-akumulasi', $data)->setPaper('legal');

            return $pdf->stream('Rekap Akumulasi Invoice '.date('d-m-Y').'-'.$data['consolidator']->NAMACONSOLIDATOR.'.pdf');
            
        endif;
        
        return back()->with('error', 'Data tidak ditemukan.')->withInput();
    }
    
    public function invoicePrintRekap(Request $request)
    {
        $consolidator_id = $request->consolidator_id;
        $start = $request->tanggal.' 00:00:00';
        $end = date('Y-m-d', strtotime('+1 Day', strtotime($request->tanggal))).' 00:00:00';
        $type = $request->type;
        
        $data['consolidator'] = \App\Models\Consolidator::find($consolidator_id);
        $data['invoices'] = \App\Models\Invoice::select('*')
                ->join('tmanifest','invoice_import.manifest_id','=','tmanifest.TMANIFEST_PK')
                ->where('tmanifest.TCONSOLIDATOR_FK', $consolidator_id)
                ->where('tmanifest.tglrelease',$request->tanggal)
//                ->where('invoice_import.created_at','>=',$start)
//                ->where('invoice_import.created_at','<',$end)
                ->where('tmanifest.INVOICE', $type)
                ->get();
        
        if(count($data['invoices']) > 0):
            $sum_total = array();
            foreach ($data['invoices'] as $invoice):
                $sum_total[] = $invoice->sub_total;        
            endforeach;
            
            $data['sub_total'] = array_sum($sum_total);
            if(isset($request->free_ppn)):
                $data['ppn'] = 0;
            else:
                $data['ppn'] = $data['sub_total']*10/100;
            endif;
            $data['materai'] = ($data['sub_total'] + $data['ppn'] >= 5000000) ? '10000' : '0';
            $data['total'] = round($data['sub_total'] + $data['ppn'] + $data['materai']);           
            $data['terbilang'] = ucwords($this->terbilang($data['total']))." Rupiah";
            $data['tgl_cetak'] = $request->tgl_cetak;

            return \View('print.invoice-rekap', $data);
            
            $pdf = \PDF::loadView('print.invoice-rekap', $data)->setPaper('legal');

            return $pdf->stream('Rekap Invoice '.date('d-m-Y').'-'.$data['consolidator']->NAMACONSOLIDATOR.'.pdf');
            
        endif;
        
        return back()->with('error', 'Data tidak ditemukan.')->withInput();
    }
    
    public function tarifIndex()
    {
        if ( !$this->access->can('show.tarif.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Daftar Tarif";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Daftar Tarif'
            ]
        ];        
        
        return view('invoice.index-tarif')->with($data);
    }
    
    public function tarifCreate()
    {
        if ( !$this->access->can('show.tarif.create') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Create Tarif";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-tarif-index'),
                'title' => 'Daftar Tarif'
            ],
            [
                'action' => '',
                'title' => 'Create'
            ]
        ];         
        
        $data['consolidators'] = \App\Models\Consolidator::select('TCONSOLIDATOR_PK as id','NAMACONSOLIDATOR as name')->get();
        
        return view('invoice.create-tarif')->with($data);
    }

    public function tarifView($id)
    {
        if ( !$this->access->can('show.tarif.view') ) {
            return view('errors.no-access');
        }
        
        $tarif = \DB::table('invoice_tarif')->find($id);
        
        $data['page_title'] = "Daftar Item Tarif ".$tarif->type;
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-tarif-index'),
                'title' => 'Daftar Tarif'
            ],
            [
                'action' => '',
                'title' => "Daftar Item Tarif ".$tarif->type
            ]
        ];        
        
        $data['tarif'] = $tarif;
        
        return view('invoice.view-tarif')->with($data);
    }
    
    public function tarifEdit($id)
    {
        if ( !$this->access->can('show.tarif.edit') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Edit Tarif";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-tarif-index'),
                'title' => 'Daftar Tarif'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];         
        $data['tarif'] = \App\Models\InvoiceTarif::find($id);
        $data['consolidators'] = \App\Models\Consolidator::select('TCONSOLIDATOR_PK as id','NAMACONSOLIDATOR as name')->get();
        
        return view('invoice.update-tarif')->with($data);
    }
    
    public function tarifStore(Request $request)
    {
        if ( !$this->access->can('store.tarif.create') ) {
            return view('errors.no-access');
        }
        
        $validator = \Validator::make($request->all(), [
            'consolidator_id' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['_token']);
        $data['UID'] = \Auth::getUser()->name;
        
        $insert_id = \App\Models\InvoiceTarif::insertGetId($data);
        
        if($insert_id){
            return redirect()->route('invoice-tarif-index')->with('success', 'Tarif has been created.');
        }
        
        return back()->with('error', 'Tarif cannot create, please try again.')->withInput();
    }
    
    public function tarifUpdate(Request $request, $id)
    {
        if ( !$this->access->can('show.tarif.edit') ) {
            return view('errors.no-access');
        }
        
        $validator = \Validator::make($request->all(), [
            'consolidator_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['_token']);
        if(!isset($data['surcharge'])) { $data['surcharge'] = 0; }
        if(!isset($data['cbm'])) { $data['cbm'] = 0; }
        if(!isset($data['pembulatan'])) { $data['pembulatan'] = 0; }

        $update = \App\Models\InvoiceTarif::where('id', $id)->update($data);
        
        if($update){
            return redirect()->route('invoice-tarif-index')->with('success', 'Tarif has been updated.');
        }
        
        return back()->with('error', 'Tarif cannot update, please try again.')->withInput();
    }
    
    public function tarifDestroy($id)
    {
        \App\Models\InvoiceTarif::where('id', $id)->delete();
        return back()->with('success', 'Invoice tarif has been deleted.'); 
    }
    
    public function tarifItemEdit($id)
    {
        if ( !$this->access->can('show.tarif.item.edit') ) {
            return view('errors.no-access');
        }
        
        $tarif_item = \DB::table('invoice_tarif_item')->find($id);
        
        $data['page_title'] = "Edit Item Tarif ".$tarif_item->description;
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-tarif-index'),
                'title' => 'Daftar Tarif'
            ],
            [
                'action' => '',
                'title' => "Edit Item Tarif ".$tarif_item->description
            ]
        ];        
        
        $data['item'] = $tarif_item;
        
        return view('invoice.edit-tarif')->with($data);
    }
    
    public function tarifItemUpdate(Request $request, $id)
    {
        if ( !$this->access->can('update.tarif.item.edit') ) {
            return view('errors.no-access');
        }
        
        unset($request['_token']);
        
        //UPDATE TARIF
        $update = \DB::table('invoice_tarif_item')->where('id', $id)
            ->update($request->all());

        if($update){

            return back()->with('success', 'LCL Register has been updated.');                   
        }
        
        return back()->with('error', 'Something wrong, please try again.')->withInput();
    }
    
    
//    FCL INVOICE
    public function invoiceNctIndex()
    {
        if ( !$this->access->can('show.invoicenct.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Invoice";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Invoice'
            ]
        ];        

        return view('invoice.index-invoice-nct')->with($data);
    }
    
    public function invoiceNctEdit($id)
    {
        if ( !$this->access->can('edit.invoiceNct.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Edit Invoice";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-nct-index'),
                'title' => 'Invoice'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['invoice'] = \App\Models\InvoiceNct::find($id);
        $data['penumpukan'] = \App\Models\InvoiceNctPenumpukan::where('invoice_nct_id', $data['invoice']->id)->get();
        $data['gerakan'] = \App\Models\InvoiceNctGerakan::where('invoice_nct_id', $data['invoice']->id)->orderBy('lokasi_sandar', 'ASC')->get();
        $data['tarif'] = \App\Models\InvoiceTarifNct::get();
        $data['terbilang'] = ucwords($this->terbilang($data['invoice']->total))." Rupiah";
        
        return view('invoice.edit-invoice-nct')->with($data);
    }
    
    public function invoiceNctDestroy($id)
    {
        \App\Models\InvoiceNct::where('id', $id)->delete();
        \App\Models\InvoiceNctPenumpukan::where('invoice_nct_id', $id)->delete();
        \App\Models\InvoiceNctGerakan::where('invoice_nct_id', $id)->delete();
        
        return back()->with('success', 'Invoice has been deleted.'); 
    }
    
    public function invoiceNctPrint($id)
    {
        $data['invoice'] = \App\Models\InvoiceNct::find($id);
        $data['penumpukan'] = \App\Models\InvoiceNctPenumpukan::where('invoice_nct_id', $data['invoice']->id)->get();
        $data['gerakan'] = \App\Models\InvoiceNctGerakan::where('invoice_nct_id', $data['invoice']->id)->orderBy('lokasi_sandar', 'ASC')->get();
        $data['tarif'] = \App\Models\InvoiceTarifNct::get();
        $data['terbilang'] = ucwords($this->terbilang($data['invoice']->total))." Rupiah";

        return view('print.invoice-nct')->with($data);

        $pdf = \PDF::loadView('print.invoice-nct', $data)->setPaper('legal');
        return $pdf->stream($data['invoice']->no_invoice.'.pdf');
    }
    
    public function tarifNctIndex()
    {
        if ( !$this->access->can('show.tarifnct.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Daftar Tarif";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Daftar Tarif'
            ]
        ];        
        
        return view('invoice.index-tarif-nct')->with($data);
    }
    
    public function releaseNctIndex()
    {
        if ( !$this->access->can('show.invoice.releasenct.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index Invoice Release', 'slug' => 'show.invoice.releasenct.index', 'description' => ''));
        
        $data['page_title'] = "Invoice Release FCL";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Invoice Release FCL'
            ]
        ];        
        
//        $data['perusahaans'] = DBPerusahaan::select('TPERUSAHAAN_PK as id', 'NAMAPERUSAHAAN as name')->get();
        
        return view('invoice.index-release-fcl')->with($data);
    }
    
    public function updateInvoiceRdm(Request $request)
    {
        $invoices = \App\Models\Invoice::select('invoice_import.*')               
                ->join('tmanifest','invoice_import.manifest_id','=','tmanifest.TMANIFEST_PK')
                ->where('tmanifest.TCONSOLIDATOR_FK', $request->consolidator_id)
                ->where('tmanifest.tglrelease','>=',$request->start_date)
                ->where('tmanifest.tglrelease','<=',$request->end_date)
                ->where('tmanifest.INVOICE', $request->type)
//                ->where('invoice_import.rdm', 0)
                ->get();
        
        $i = 0;
        foreach ($invoices as $invoice):
            $rdm =  $request->tarif_rdm * $invoice->cbm;
            
            $array_total = array(
                $rdm,
                $invoice->storage,
                $invoice->storage_masa1,
                $invoice->storage_masa2,
                $invoice->storage_masa3,
                $invoice->harga_behandle,
                $invoice->adm,
                $invoice->dg_surcharge,
                $invoice->weight_surcharge
            );
            $subtotal = array_sum($array_total);     
            
            // Update Invoice
            $update = \App\Models\Invoice::find($invoice->id);
            $update->rdm = $rdm;
            $update->sub_total = $subtotal;
            $update->save();
            
            $i++;
        endforeach;
        
        if($i > 0){
            return back()->with('success', $i.' invoice berhasil di update.'); 
        }
        
        return back()->with('error', 'Tidak ada invoice yang di update.');
    }

    public function invoiceExtend(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $old_invoice = InvoiceNct::find($invoice_id);
        $hari_terminal = InvoiceNctPenumpukan::where('invoice_nct_id', $invoice_id)->groupBy('size')->sum('lama_timbun');

        // Create Invoice Header
        $invoice_nct = new \App\Models\InvoiceNct;
        $invoice_nct->no_invoice = $request->no_invoice;
        $invoice_nct->no_pajak = $request->no_pajak;
        $invoice_nct->consignee = $old_invoice->consignee;
        $invoice_nct->npwp = $old_invoice->npwp;
        $invoice_nct->alamat = $old_invoice->alamat;
        $invoice_nct->consignee_id = $old_invoice->consignee_id;
        $invoice_nct->vessel = $old_invoice->vessel;
        $invoice_nct->voy = $old_invoice->voy;
        $invoice_nct->no_do = $old_invoice->no_do;
        $invoice_nct->no_bl = $old_invoice->no_bl;
        $invoice_nct->eta = $old_invoice->eta;
        $invoice_nct->gateout_terminal = $old_invoice->gateout_terminal;
        $invoice_nct->gateout_tps = $old_invoice->gateout_tps;
        $invoice_nct->type = $old_invoice->type;
        $invoice_nct->extend = 'Y';
        $invoice_nct->tgl_extend = $request->tgl_extend;
        $invoice_nct->tps_asal = $old_invoice->tps_asal;
        $invoice_nct->uid = \Auth::getUser()->name;

        if($invoice_nct->save()) {
            $old_penumpukan = InvoiceNctPenumpukan::where('invoice_nct_id', $invoice_id)->get();
            $total_qty = 0;
            foreach ($old_penumpukan as $op) {
                if($op->lokasi_sandar == 'TPS') {
                    $total_qty += $op->qty;
                    $tarif = \App\Models\InvoiceTarifNct::where(array('size' => $op->size, 'type' => $invoice_nct->type, 'lokasi_sandar' => $op->lokasi_sandar))->first();

                    // PENUMPUKAN
                    $invoice_penumpukan = new \App\Models\InvoiceNctPenumpukan;
                    $invoice_penumpukan->invoice_nct_id = $invoice_nct->id;
                    $invoice_penumpukan->lokasi_sandar = $op->lokasi_sandar;
                    $invoice_penumpukan->size = $op->size;
                    $invoice_penumpukan->qty = $op->qty;

                    $date1 = date_create($op->enddate);
                    $date2 = date_create($invoice_nct->tgl_extend);
                    $diff = date_diff($date1, $date2);
                    $hari = $diff->format("%a");

                    $invoice_penumpukan->startdate = $op->enddate;
                    $invoice_penumpukan->enddate = $invoice_nct->tgl_extend;
                    $invoice_penumpukan->lama_timbun = $hari;

                    if($hari_terminal >= 8){
                        $invoice_penumpukan->hari_masa1 = 0;
                        $invoice_penumpukan->hari_masa2 = $hari;
                    }else{
                        $sisa_hari = abs(8-$hari_terminal);
                        $hari_depo_masa1 = min(array($hari, $sisa_hari));
                        $invoice_penumpukan->hari_masa1 = $hari_depo_masa1;
                        $invoice_penumpukan->hari_masa2 = abs($hari-$hari_depo_masa1);
                    }
                    $invoice_penumpukan->hari_masa3 = 0;
                    $invoice_penumpukan->hari_masa4 = 0;

                    $invoice_penumpukan->masa1 = ($invoice_penumpukan->hari_masa1 * $tarif->masa1 * 2) * $op->qty;
                    $invoice_penumpukan->masa2 = ($invoice_penumpukan->hari_masa2 * $tarif->masa2 * 3) * $op->qty;
                    $invoice_penumpukan->masa3 = ($invoice_penumpukan->hari_masa3 * $tarif->masa3) * $op->qty;
                    $invoice_penumpukan->masa4 = ($invoice_penumpukan->hari_masa4 * $tarif->masa4) * $op->qty;
                    $invoice_penumpukan->total = array_sum(array($invoice_penumpukan->masa1,$invoice_penumpukan->masa2,$invoice_penumpukan->masa3,$invoice_penumpukan->masa4));

                    $invoice_penumpukan->save();
                }
            }
        }

        $update_nct = \App\Models\InvoiceNct::find($invoice_nct->id);

        $total_penumpukan = \App\Models\InvoiceNctPenumpukan::where('invoice_nct_id', $invoice_nct->id)->sum('total');

        if($invoice_nct->tps_asal == 'KOJA'):
            $update_nct->perawatan_it = $total_qty * 90000;
            if($invoice_nct->type == 'BB'){
                $total_penumpukan_tps = \App\Models\InvoiceNctPenumpukan::where('invoice_nct_id', $invoice_nct->id)->where('lokasi_sandar','TPS')->sum('total');
                $update_nct->surcharge = ($total_penumpukan_tps)*(25/100);
            }
            $update_nct->administrasi = 20000;
        else:
            $update_nct->administrasi = $total_qty * 100000;
        endif;

        $update_nct->total_non_ppn = $total_penumpukan + $update_nct->administrasi + $update_nct->perawatan_it + $update_nct->surcharge;
        $update_nct->ppn = $update_nct->total_non_ppn * 10/100;
        if(($update_nct->total_non_ppn+$update_nct->ppn) >= 5000000){
            $materai = 10000;
        }else{
            $materai = 0;
        }
        $update_nct->materai = $materai;
        $update_nct->total = $update_nct->total_non_ppn+$update_nct->ppn+$update_nct->materai;

        if($update_nct->save()) {
            return back()->with('success', 'Invoice perpanjangan berhasih dibuat.');
        }
    }

}
