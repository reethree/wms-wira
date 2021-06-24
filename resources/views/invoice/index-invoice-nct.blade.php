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
    
    var grid = $("#fclInvoicesGrid"), headerRow, rowHight, resizeSpanHeight;

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
    
    function gridCompleteEvent()
    {
        var ids = jQuery("#fclInvoicesGrid").jqGrid('getDataIDs'),
            edt = '',
            del = ''; 
        for(var i=0;i < ids.length;i++){ 
            var cl = ids[i];

            rowdata = $('#fclInvoicesGrid').getRowData(cl);
            if(rowdata.payment_status == 'Waiting') {
                $("#" + cl).find("td").css("background-color", "yellow").css("color", "#000");
            }
            if(rowdata.payment_status == 'Paid') {
                $("#" + cl).find("td").css("background-color", "green").css("color", "#FFF");
            }

            edt = '<a href="{{ route("invoice-nct-edit",'') }}/'+cl+'"><i class="fa fa-pencil"></i></a> ';
            del = '<a href="{{ route("invoice-nct-delete",'') }}/'+cl+'" onclick="if (confirm(\'Are You Sure ?\')){return true; }else{return false; };"><i class="fa fa-close"></i></a>';
            jQuery("#fclInvoicesGrid").jqGrid('setRowData',ids[i],{action:edt+' '+del}); 
        } 
    }

    function onSelectRowEvent()
    {
        rowid = $('#fclInvoicesGrid').jqGrid('getGridParam', 'selrow');
        rowdata = $('#fclInvoicesGrid').getRowData(rowid);

        $("#invoice_id").val(rowdata.id);
    }

    $(document).ready(function()
    {
        $('#extend-btn').on("click", function(){
            rowid = $('#fclInvoicesGrid').jqGrid('getGridParam', 'selrow');
            if(rowid){
                $('#extend-invoice-modal').modal('show');
            }else{
                alert('Please select the invoice that will be extended.');
            }
        });

        $('#accurate-btn').on("click", function(){
            rowid = $('#fclInvoicesGrid').jqGrid('getGridParam', 'selrow');
            rowdata = $('#fclInvoicesGrid').getRowData(rowid);
            if(rowid){
                $("#accurate-invoice-id").val(rowid);
                $("#accurate-consignee").val(rowdata.consignee);
                $("#accurate-no-invoice").val(rowdata.no_invoice);
                $('#upload-accurate-modal').modal('show');
            }else{
                alert('Please select the invoice that will be upload to accurate.');
            }
        });

        $('#upload-accurate-btn').on('click', function(){
            var invID = $("#accurate-invoice-id").val();
            var code = $("#kode_perusahaan").val();
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
                            saveInvoice(invID, code);
                        };
                    }
                });
            }else{
                swal.fire("Oops!",'Kode Perusahaan belum di isi');
                return false;
            }
        });
    });

    function saveInvoice(invoice_id,kode) {
        $.ajax({
            url:"{{ route('accurate-upload') }}",
            method:'POST',
            data:{
                id: invoice_id,
                code: kode,
                type: 'fcl',
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
                $("#fclInvoicesGrid").jqGrid().trigger('reloadGrid');
            }
        });
    }
    
</script>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">FCL Invoices Lists</h3>
        <div class="box-tools">
            <button class="btn btn-danger btn-sm" id="accurate-btn"><i class="fa fa-upload"></i> Upload to Accurate</button>
            <button class="btn btn-warning btn-sm" id="extend-btn"><i class="fa fa-forward"></i> Extend</button>
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
            GridRender::setGridId("fclInvoicesGrid")
            ->enableFilterToolbar()
            ->setGridOption('mtype', 'POST')
            ->setGridOption('url', URL::to('/invoice/fcl/grid-data?_token='.csrf_token()))
            ->setFileProperty('title', 'LCL Invoices') //Laravel Excel File Property
            ->setFileProperty('creator', 'Reza') //Laravel Excel File Property
            ->setSheetProperty('fitToPage', true) //Laravel Excel Sheet Property
            ->setSheetProperty('fitToHeight', true)
            ->setGridOption('rowNum', 20)
            ->setGridOption('shrinkToFit', true)
            ->setGridOption('sortname','updated_at')
            ->setGridOption('sortorder','DESC')
            ->setGridOption('rownumbers', true)
            ->setGridOption('height', '295')
            ->setGridOption('rowList',array(20,50,100))
            ->setGridOption('useColSpanStyle', true)
            ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
            ->setNavigatorOptions('view',array('closeOnEscape'=>false))
            ->setFilterToolbarOptions(array('autosearch'=>true))
            ->setGridEvent('gridComplete', 'gridCompleteEvent')
            ->setGridEvent('onSelectRow', 'onSelectRowEvent')
            ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
            ->addColumn(array('key'=>true,'index'=>'id','hidden'=>true))
            ->addColumn(array('label'=>'Extend','index'=>'extend','width'=>60,'align'=>'center'))
            ->addColumn(array('label'=>'Jenis','index'=>'type','width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'No. Invoice','index'=>'no_invoice','width'=>160,'align'=>'center'))
            ->addColumn(array('label'=>'No. Pajak','index'=>'no_pajak','width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Consignee','index'=>'consignee','width'=>350,'align'=>'left'))
            ->addColumn(array('label'=>'NPWP','index'=>'npwp','width'=>160,'align'=>'center'))
            ->addColumn(array('label'=>'Alamat','index'=>'alamat','width'=>120, 'align'=>'center','hidden'=>true))
            ->addColumn(array('index'=>'consignee_id','hidden'=>true))
            ->addColumn(array('label'=>'Vessel','index'=>'vessel', 'width'=>160))
            ->addColumn(array('label'=>'Voy','index'=>'voy','width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'No. DO','index'=>'no_do','width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'No. B/L','index'=>'no_bl','width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'ETA','index'=>'eta', 'width'=>120, 'align'=>'center'))
            ->addColumn(array('label'=>'Terminal Out','index'=>'gateout_terminal', 'width'=>120, 'align'=>'center'))
            ->addColumn(array('label'=>'TPS Out','index'=>'gateout_tps', 'width'=>120, 'align'=>'center'))
            ->addColumn(array('label'=>'Administrasi','index'=>'administrasi','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Sebelum PPN','index'=>'total_non_ppn','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'PPN 10%','index'=>'ppn','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Materai','index'=>'materai','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Total','index'=>'total','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Status','index'=>'payment_status', 'width'=>160,'align'=>'center'))
            ->addColumn(array('label'=>'Created','index'=>'created_at', 'width'=>160,'align'=>'center'))
            ->addColumn(array('label'=>'Updated','index'=>'updated_at', 'width'=>160,'align'=>'center','hidden'=>true))
            ->addColumn(array('label'=>'UID','index'=>'uid','width'=>'150','align'=>'center')) 
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
                                <label class="col-sm-3 control-label">No. Invoice</label>
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

<div id="extend-invoice-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Extend Invoice</h4>
            </div>
            <form id="create-invoice-form" class="form-horizontal" action="{{ route("invoice-extend") }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            <input name="invoice_id" type="hidden" id="invoice_id" />
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No. Invoice</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="no_invoice" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tgl. Perpanjang</label>
                                <div class="col-sm-6">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="tgl_extend" class="form-control pull-right datepicker" value="{{date('Y-m-d')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No. Pajak</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="no_pajak" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
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
        jQuery("#fclInvoicesGrid").jqGrid('setGridParam',{url:"{{URL::to('/invoice/fcl/grid-data')}}?startdate="+startdate+"&enddate="+enddate}).trigger("reloadGrid");
        return false;
    });
    
    $('#cetak-rekap').on('click', function(){
        $('#cetak-rekap-modal').modal('show');
    });
    
    $.fn.bootstrapSwitch.defaults.onColor = 'danger';
    $.fn.bootstrapSwitch.defaults.onText = 'Yes';
    $.fn.bootstrapSwitch.defaults.offText = 'No';
    $("input[type='checkbox']").bootstrapSwitch();
</script>

@endsection