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
        .ui-jqgrid tr.jqgrow td {
            height: auto;
        }
        .ui-jqgrid tr.jqgrow td { word-wrap: break-word; /* IE 5.5+ and CSS3 */ white-space: pre-wrap; /* CSS3 */ white-space: -moz-pre-wrap; /* Mozilla, since 1999 */ white-space: -pre-wrap; /* Opera 4-6 */ white-space: -o-pre-wrap; /* Opera 7 */ overflow: hidden; height: auto; vertical-align: middle; padding-top: 3px; padding-bottom: 3px; }
        /*.ui-jqgrid tr.jqgrow td { white-space: normal !important; height: auto; vertical-align: text-top; padding-top: 2px; }*/
        th.ui-th-column div {  word-wrap: break-word; /* IE 5.5+ and CSS3 */ white-space: pre-wrap; /* CSS3 */ white-space: -moz-pre-wrap; /* Mozilla, since 1999 */ white-space: -pre-wrap; /* Opera 4-6 */ white-space: -o-pre-wrap; /* Opera 7 */ overflow: hidden; height: auto; vertical-align: middle; padding-top: 3px; padding-bottom: 3px;  }
    </style>
    <script>

        var grid = $("#lclInvoicesRekapGrid"), headerRow, rowHight, resizeSpanHeight;

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
                rowid = $('#lclInvoicesRekapGrid').jqGrid('getGridParam', 'selrow');
                rowdata = $('#lclInvoicesRekapGrid').getRowData(rowid);
                if(rowid){
                    $("#accurate-invoice-id").val(rowid);
                    $("#accurate-consignee").val(rowdata.consolidator);
                    $("#accurate-no-invoice").val(rowdata.no_kwitansi);
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
            alert('Please Wait...');
            $.ajax({
                url:"{{ route('accurate-upload') }}",
                method:'POST',
                data:{
                    id: invoice_id,
                    code: kode,
                    type: 'rekap',
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
            var grid = jQuery("#lclInvoicesRekapGrid"),
                ids = grid.jqGrid('getDataIDs'),
                edt = '',
                del = '';
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];

                edt = '<a href="#" onclick="window.open(\'{{ route("invoice-rekap-edit","") }}/'+cl+'\', \n' +
                    '                         \'newwindow\', \n' +
                    '                         \'width=900,height=600\'); \n' +
                    '              return false;"><i class="fa fa-print"></i></a> ';
                del = '<a href="{{ route("invoice-rekap-delete",'') }}/'+cl+'" onclick="if (confirm(\'Are You Sure ?\')){return true; }else{return false; };"><i class="fa fa-close"></i></a>';
                jQuery("#lclInvoicesRekapGrid").jqGrid('setRowData',ids[i],{action:edt+' '+del});
            }

        }

    </script>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">LCL Invoices Rekap Lists</h3>
            <div class="box-tools">
                <button class="btn btn-danger btn-sm" id="accurate-btn"><i class="fa fa-upload"></i> Upload to Accurate</button>
                <button class="btn btn-info btn-sm" id="create-rekap"><i class="fa fa-plus"></i> Create Rekap</button>
            </div>
        </div>
        <div class="box-body table-responsive">
{{--            <div class="row" style="margin-bottom: 30px;margin-right: 0;">--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="col-xs-12">Search By Date</div>--}}
{{--                    <div class="col-xs-4">--}}
{{--                        <div class="input-group date">--}}
{{--                            <div class="input-group-addon">--}}
{{--                                <i class="fa fa-calendar"></i>--}}
{{--                            </div>--}}
{{--                            <input type="text" id="startdate" class="form-control pull-right datepicker">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-xs-1">--}}
{{--                        s/d--}}
{{--                    </div>--}}
{{--                    <div class="col-xs-4">--}}
{{--                        <div class="input-group date">--}}
{{--                            <div class="input-group-addon">--}}
{{--                                <i class="fa fa-calendar"></i>--}}
{{--                            </div>--}}
{{--                            <input type="text" id="enddate" class="form-control pull-right datepicker">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-xs-2">--}}
{{--                        <button id="searchByDateBtn" class="btn btn-default">Search</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            {{
                GridRender::setGridId("lclInvoicesRekapGrid")
                ->enableFilterToolbar()
                ->setGridOption('mtype', 'POST')
                ->setGridOption('url', URL::to('/invoice/rekap/grid-data?_token='.csrf_token()))
                ->setFileProperty('title', 'LCL Invoices Rekap') //Laravel Excel File Property
                ->setFileProperty('creator', 'Reza') //Laravel Excel File Property
                ->setSheetProperty('fitToPage', true) //Laravel Excel Sheet Property
                ->setSheetProperty('fitToHeight', true)
                ->setGridOption('rowNum', 50)
                ->setGridOption('rownumWidth', 50)
                ->setGridOption('shrinkToFit', true)
                ->setGridOption('sortname','print_date')
                ->setGridOption('sortorder','DESC')
                ->setGridOption('rownumbers', true)
                ->setGridOption('height', '375')
                ->setGridOption('rowList',array(50,100,200))
                ->setGridOption('useColSpanStyle', true)
                ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
                ->setNavigatorOptions('view',array('closeOnEscape'=>false))
                ->setFilterToolbarOptions(array('autosearch'=>true))
                ->setGridEvent('gridComplete', 'gridCompleteEvent')
                ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
                ->addColumn(array('key'=>true,'index'=>'id','hidden'=>true))
                ->addColumn(array('index'=>'consolidator_id','hidden'=>true))
                ->addColumn(array('label'=>'Accurate','index'=>'uploaded_accurate', 'width'=>60,'align'=>'center'))
                ->addColumn(array('label'=>'RDM','index'=>'rdm_only', 'width'=>60,'align'=>'center'))
                ->addColumn(array('label'=>'Type','index'=>'type', 'width'=>60,'align'=>'center'))
                ->addColumn(array('label'=>'No. Kwitansi','index'=>'no_kwitansi','width'=>150,'align'=>'center'))
                ->addColumn(array('label'=>'Consolidator','index'=>'consolidator','width'=>250))
                ->addColumn(array('label'=>'Tanggal<br />Mulai','index'=>'start_date', 'width'=>120, 'align'=>'center'))
                ->addColumn(array('label'=>'Tanggal<br />Akhir','index'=>'end_date', 'width'=>120, 'align'=>'center'))
                ->addColumn(array('label'=>'No. Nota','index'=>'invoice_no','width'=>300))
                ->addColumn(array('label'=>'Subtotal','index'=>'sub_total', 'width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
                ->addColumn(array('label'=>'PPN','index'=>'ppn', 'width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
                ->addColumn(array('label'=>'Materai','index'=>'materai','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
                ->addColumn(array('label'=>'Total','index'=>'total','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
                ->addColumn(array('label'=>'Tanggal<br />Cetak','index'=>'print_date', 'width'=>120,'align'=>'center'))
                ->addColumn(array('label'=>'UID','index'=>'uid', 'width'=>150))
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
                                    <label class="col-sm-3 control-label">No. Kwitansi</label>
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

    <div id="create-rekap-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Create Rekap</h4>
                </div>
                <form class="form-horizontal" action="{{ route('invoice-rekap-create') }}" method="POST">
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


@endsection

@section('custom_css')

    <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/datepicker/datepicker3.css") }}">
    <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.css") }}">

@endsection

@section('custom_js')

    <script src="{{ asset("/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js") }}"></script>
    <script src="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.js") }}"></script>
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
            jQuery("#lclInvoicesRekapGrid").jqGrid('setGridParam',{url:"{{URL::to('/invoice/grid-data')}}?startdate="+startdate+"&enddate="+enddate}).trigger("reloadGrid");
            return false;
        });

        $('#create-rekap').on('click', function(){
            $('#create-rekap-modal').modal('show');
        });

        $.fn.bootstrapSwitch.defaults.onColor = 'danger';
        $.fn.bootstrapSwitch.defaults.onText = 'Yes';
        $.fn.bootstrapSwitch.defaults.offText = 'No';
        $("input[type='checkbox']").bootstrapSwitch();
    </script>

@endsection