@extends('layout')

@section('content')
<style>
    .datepicker.dropdown-menu {
        z-index: 9999 !important;
    }
    th.ui-th-column div{
        white-space:normal !important;
        height:auto !important;
        padding:2px;
    }
</style>
<script>
    
    var grid = $("#lclInvoicesGrid"), headerRow, rowHight, resizeSpanHeight;

    // get the header row which contains
    headerRow = grid.closest("div.ui-jqgrid-view")
        .find("table.ui-jqgrid-htable>thead>tr.ui-jqgrid-labels");

    // increase the height of the resizing span
    resizeSpanHeight = 'height: ' + headerRow.height() +
        'px !important; cursor: col-resize;';
    headerRow.find("span.ui-jqgrid-resize").each(function () {
        this.style.cssText = resizeSpanHeight;
    });

    // set position of the dive with the column header text to the middle
    rowHight = headerRow.height();
    headerRow.find("div.ui-jqgrid-sortable").each(function () {
        var ts = $(this);
        ts.css('top', (rowHight - ts.outerHeight()) / 2 + 'px');
    });

    $(document).ready(function() {
        $('#accurate-btn').on("click", function(){
            rowid = $('#lclInvoicesGrid').jqGrid('getGridParam', 'selrow');
            rowdata = $('#lclInvoicesGrid').getRowData(rowid);
            if(rowid){
                $("#accurate-invoice-id").val(rowid);
                $("#accurate-consignee").val(rowdata.NAMACONSOLIDATOR);
                $("#accurate-no-invoice").val(rowdata.no_invoice);
                $('#upload-accurate-modal').modal('show');
            }else{
                alert('Please select the invoice that will be upload to accurate.');
            }
        });

        $('#upload-accurate-btn').on('click', function(){
            var invID = $("#accurate-invoice-id").val();
            var code = $("#kode_perusahaan").val();
            var ket = $("#keterangan").val();
            if(code) {
                $("#kode_perusahaan").val('');
                $('#upload-accurate-modal').modal('hide');

                $.ajax({
                    url: "{{ route('accurate-oauth') }}",
                    method: 'GET',
                    success: function (res) {
                        var win = window.open(
                            res.url,
                            "Accurate Oauth Authorization",
                            "width=500,height=400"
                        );
                        win.onbeforeunload = function () {
                            saveInvoice(invID, code, ket);
                        };
                    }
                });
            }else{
                swal.fire("Oops!",'Kode Perusahaan belum di isi');
                return false;
            }
        });
    });

    function saveInvoice(invoice_id,kode,ket) {
        $.ajax({
            url:"{{ route('accurate-upload') }}",
            method:'POST',
            data:{
                id: invoice_id,
                code: kode,
                type: 'lcl',
                keterangan: ket,
                _token: '{{ csrf_token() }}'
            },
            beforeSend:function(){
                swal.fire({
                    title: "Mohon tunggu.",
                    text: "Proses sedang berlangsung...",
                    showConfirmButton: false
                });
            },
            success:function(res) {
                if(res.success) {
                    swal.fire("Success",res.message);
                } else {
                    swal.fire("Oops!",res.message);
                }
                $("#lclInvoicesGrid").jqGrid().trigger('reloadGrid');
            }
        });
    }

    function gridCompleteEvent()
    {
        var ids = jQuery("#lclInvoicesGrid").jqGrid('getDataIDs'),
            edt = '',
            del = ''; 
        for(var i=0;i < ids.length;i++){ 
            var cl = ids[i];

            rowdata = $('#lclInvoicesGrid').getRowData(cl);

            if(rowdata.uploaded_accurate == 1) {
                $("#" + cl).find("td").css("color", "blue");
            }
            
            edt = '<a href="{{ route("invoice-edit",'') }}/'+cl+'"><i class="fa fa-pencil"></i></a> ';
            del = '<a href="{{ route("invoice-delete",'') }}/'+cl+'" onclick="if (confirm(\'Are You Sure ?\')){return true; }else{return false; };"><i class="fa fa-close"></i></a>';
            jQuery("#lclInvoicesGrid").jqGrid('setRowData',ids[i],{action:edt+' '+del}); 
        } 
    }
    
</script>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">LCL Invoices Lists</h3>
        <div class="box-tools">
            <button class="btn btn-danger btn-sm" id="accurate-btn"><i class="fa fa-upload"></i> Upload to Accurate</button>
            <!--<button class="btn btn-danger btn-sm" id="update-rdm"><i class="fa fa-refresh"></i> UPDATE RDM</button>&nbsp;&nbsp;&nbsp;|||&nbsp;&nbsp;&nbsp;-->
{{--            <button class="btn btn-info btn-sm" id="cetak-rekap"><i class="fa fa-print"></i> REKAP INVOICE</button>--}}
{{--            <button class="btn btn-warning btn-sm" id="cetak-rekap-akumulasi"><i class="fa fa-print"></i> REKAP AKUMULASI</button>--}}
        </div>
    </div>
    <div class="box-body table-responsive">
        <div class="row" style="margin-bottom: 30px;margin-right: 0;">
            <div class="col-md-6">
                <div class="col-xs-12">Search By Date</div>
                <div class="col-xs-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" id="startdate" class="form-control pull-right datepicker">
                    </div>
                </div>
                <div class="col-xs-1">
                    s/d
                </div>
                <div class="col-xs-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" id="enddate" class="form-control pull-right datepicker">
                    </div>
                </div>
                <div class="col-xs-2">
                    <button id="searchByDateBtn" class="btn btn-default">Search</button>
                </div>
            </div>
        </div>
        {{
            GridRender::setGridId("lclInvoicesGrid")
            ->enableFilterToolbar()
            ->setGridOption('mtype', 'POST')
            ->setGridOption('url', URL::to('/invoice/grid-data?_token='.csrf_token()))
            ->setFileProperty('title', 'LCL Invoices') //Laravel Excel File Property
            ->setFileProperty('creator', 'Reza') //Laravel Excel File Property
            ->setSheetProperty('fitToPage', true) //Laravel Excel Sheet Property
            ->setSheetProperty('fitToHeight', true)
            ->setGridOption('rowNum', 50)
            ->setGridOption('rownumWidth', 50)
            ->setGridOption('shrinkToFit', true)
            ->setGridOption('sortname','updated_at')
            ->setGridOption('sortorder','DESC')
            ->setGridOption('rownumbers', true)
            ->setGridOption('height', '300')
            ->setGridOption('rowList',array(50,100,200))
            ->setGridOption('useColSpanStyle', true)
            ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
            ->setNavigatorOptions('view',array('closeOnEscape'=>false))
            ->setFilterToolbarOptions(array('autosearch'=>true))
            ->setGridEvent('gridComplete', 'gridCompleteEvent')
            ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
            ->addColumn(array('key'=>true,'index'=>'id','hidden'=>true))
            
//            ->addColumn(array('label'=>'No. Joborder','index'=>'NOJOBORDER','width'=>160))
//            ->addColumn(array('label'=>'No. MBL','index'=>'NOMBL','width'=>160))
//            ->addColumn(array('label'=>'Tgl. MBL','index'=>'TGL_MASTER_BL','width'=>150,'align'=>'center'))
//            ->addColumn(array('label'=>'No. BC11','index'=>'TNO_BC11','width'=>150,'align'=>'right'))
//            ->addColumn(array('label'=>'Tgl. BC11','index'=>'TTGL_BC11','width'=>150,'align'=>'center'))
//            ->addColumn(array('label'=>'No. PLP','index'=>'TNO_PLP','width'=>150,'align'=>'right'))
//            ->addColumn(array('label'=>'Tgl. PLP','index'=>'TTGL_PLP','width'=>150,'align'=>'center'))
//            ->addColumn(array('label'=>'ETA','index'=>'ETA', 'width'=>150,'align'=>'center'))
//            ->addColumn(array('label'=>'ETD','index'=>'ETD', 'width'=>150,'align'=>'center'))
            ->addColumn(array('label'=>'Accurate','index'=>'uploaded_accurate','width'=>60,'align'=>'center'))
            ->addColumn(array('label'=>'No. Nota','index'=>'no_invoice','width'=>150,'align'=>'center'))
            ->addColumn(array('label'=>'Type','index'=>'INVOICE','width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'RDM','index'=>'rdm','width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Consolidator','index'=>'NAMACONSOLIDATOR','width'=>250))
            ->addColumn(array('label'=>'Vessel','index'=>'VESSEL', 'width'=>150))
            ->addColumn(array('label'=>'Voy','index'=>'VOY','width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'No. Container','index'=>'NOCONTAINER','width'=>160))
            ->addColumn(array('label'=>'Size','index'=>'SIZE', 'width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Tanggal<br />Masuk','index'=>'tglmasuk', 'width'=>120, 'align'=>'center'))
            ->addColumn(array('label'=>'Tanggal<br />Keluar','index'=>'tglrelease', 'width'=>120, 'align'=>'center'))
            ->addColumn(array('label'=>'No. B/L','index'=>'NOHBL','width'=>160))          
            ->addColumn(array('label'=>'Consignee','index'=>'CONSIGNEE', 'width'=>250,))
            ->addColumn(array('label'=>'CBM<br r/>eq','index'=>'cbm', 'width'=>60,'align'=>'center'))
            ->addColumn(array('label'=>'Hari','index'=>'hari','width'=>60,'align'=>'center'))
            ->addColumn(array('label'=>'Bhndl','index'=>'behandle', 'width'=>60,'align'=>'center'))
            ->addColumn(array('label'=>'Warehouse<br/>Charge','index'=>'warehouse_charge','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Storage','index'=>'storage','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Masa I','index'=>'hari_masa1','width'=>100,'align'=>'center', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Masa II','index'=>'hari_masa2','width'=>100,'align'=>'center', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Masa III','index'=>'hari_masa3','width'=>100,'align'=>'center', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))           
            ->addColumn(array('label'=>'Behandle','index'=>'harga_behandle', 'width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Adm/Doc','index'=>'adm','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Surcharge','index'=>'weight_surcharge', 'width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Sub Total','index'=>'sub_total', 'width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
//            ->addColumn(array('label'=>'PPN','index'=>'ppn', 'width'=>120,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'UID','index'=>'UID', 'width'=>150))
            ->addColumn(array('label'=>'Tanggal<br/>Entry','index'=>'created_at', 'width'=>160,'align'=>'center'))
//            ->addColumn(array('label'=>'Updated','index'=>'last_update', 'width'=>150, 'search'=>false))
//            ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
            ->renderGrid()
        }}
    </div>
</div>

<div id="upload-accurate-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Input Kode Perusahaan</h4>
            </div>
            <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            <input name="invoice_id" type="hidden" id="accurate-invoice-id" />
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No. Nota</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="accurate-no-invoice" name="no_invoice" required readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nama Perusahaan</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="accurate-consignee" name="nama_perusahaan" required readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Kode Perusahaan</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="kode_perusahaan" name="kode_perusahaan" required placeholder="Kode yang terdaftar di Accurate" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Keterangan</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                    <button type="button" id="upload-accurate-btn" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="cetak-rekap-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Cetak Rekap</h4>
            </div>
            <form class="form-horizontal" action="{{ route('invoice-print-rekap') }}" method="POST">
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No. Invoice</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="no_kwitansi" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Consolidator</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="consolidator_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="">Choose Consolidator</option>
                                        @foreach($consolidators as $consolidator)
                                            <option value="{{ $consolidator->id }}">{{ $consolidator->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tgl. Release</label>
                                <div class="col-sm-4">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input autocomplete="off" type="text" name="start_date" class="form-control pull-right datepicker" required>
                                    </div>
                                </div>
                                <label class="col-sm-1 control-label">s/d</label>
                                <div class="col-sm-4">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input autocomplete="off" type="text" name="end_date" class="form-control pull-right datepicker" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tgl. Cetak</label>
                                <div class="col-sm-8">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input autocomplete="off" type="text" name="tgl_cetak" class="form-control pull-right datepicker" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Type</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="type" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="">Choose Type</option>
                                        <option value="BB">BB</option>
                                        <option value="DRY">DRY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">RDM</label>
                                <div class="col-sm-5">
                                    <input type="checkbox" name="rdm_only" value="1" />
                                </div>
                            </div>
                            <div class="form-group" style="display: none;">
                                <label class="col-sm-3 control-label">Free PPN</label>
                                <div class="col-sm-5">
                                    <input type="checkbox" name="free_ppn" value="1" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                  <button type="submit" class="btn btn-primary">Cetak</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="cetak-rekap-akumulasi-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cetak Rekap Akumulasi</h4>
            </div>
            <form class="form-horizontal" action="{{ route('invoice-print-rekap-akumulasi') }}" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No. Invoice</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="no_kwitansi" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Consolidator</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="consolidator_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="">Choose Consolidator</option>
                                        @foreach($consolidators as $consolidator)
                                            <option value="{{ $consolidator->id }}">{{ $consolidator->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tgl. Invoice</label>
                                <div class="col-sm-4">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input autocomplete="off" type="text" name="start_date" class="form-control pull-right datepicker" required>
                                    </div>
                                </div>
                                <label class="col-sm-1 control-label">s/d</label>
                                <div class="col-sm-4">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input autocomplete="off" type="text" name="end_date" class="form-control pull-right datepicker" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tgl. Cetak</label>
                                <div class="col-sm-8">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input autocomplete="off" type="text" name="tgl_cetak" class="form-control pull-right datepicker" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Type</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="type" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="">Choose Type</option>
                                        <option value="BB">BB</option>
                                        <option value="DRY">DRY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Free PPN</label>
                                <div class="col-sm-5">
                                    <input type="checkbox" name="free_ppn" value="1" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="update-rdm-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Update Tarif RDM</h4>
            </div>
            <form class="form-horizontal" action="{{ route('invoice-update-rdm') }}" method="POST">
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Consolidator</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="consolidator_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="">Choose Consolidator</option>
                                        @foreach($consolidators as $consolidator)
                                            @if(str_contains($consolidator->name, 'MASAJI'))
                                                <option value="{{ $consolidator->id }}">{{ $consolidator->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tgl. Invoice</label>
                                <div class="col-sm-4">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="start_date" class="form-control pull-right datepicker" required>
                                    </div>
                                </div>
                                <label class="col-sm-1 control-label">s/d</label>
                                <div class="col-sm-4">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="end_date" class="form-control pull-right datepicker" required>
                                    </div>
                                </div>
                            </div>   
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Type</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="type" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="">Choose Type</option>
                                        <option value="BB">BB</option>
                                        <option value="DRY">DRY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tarif RDM</label>
                                <div class="col-sm-8">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            IDR
                                        </div>
                                        <input type="number" name="tarif_rdm" class="form-control" required>
                                    </div>                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                  <button type="submit" class="btn btn-danger">Update</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('custom_css')

<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/datepicker/datepicker3.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.css") }}">

@endsection

@section('custom_js')

<script src="{{ asset("/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js") }}"></script>
<script src="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.js") }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        zIndex: 99
    });
    
    $('#searchByDateBtn').on("click", function(){
        var startdate = $("#startdate").val();
        var enddate = $("#enddate").val();
        jQuery("#lclInvoicesGrid").jqGrid('setGridParam',{url:"{{URL::to('/invoice/grid-data')}}?startdate="+startdate+"&enddate="+enddate}).trigger("reloadGrid");
        return false;
    });
    
    $('#cetak-rekap').on('click', function(){
        $('#cetak-rekap-modal').modal('show');
    });
    
    $('#cetak-rekap-akumulasi').on('click', function(){
        $('#cetak-rekap-akumulasi-modal').modal('show');
    });
    
    $('#update-rdm').on('click', function(){
        $('#update-rdm-modal').modal('show');
    });
    
    $.fn.bootstrapSwitch.defaults.onColor = 'danger';
    $.fn.bootstrapSwitch.defaults.onText = 'Yes';
    $.fn.bootstrapSwitch.defaults.offText = 'No';
    $("input[type='checkbox']").bootstrapSwitch();
</script>

@endsection