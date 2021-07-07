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

        var grid = $("#lclInvoicesPlpGrid"), headerRow, rowHight, resizeSpanHeight;

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
            var grid = jQuery("#lclInvoicesPlpGrid"),
                ids = grid.jqGrid('getDataIDs'),
                edt = '',
                del = '';
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];

                edt = '<a href="{{ route("invoice-plp-edit",'') }}/'+cl+'"><i class="fa fa-pencil"></i></a> ';
                del = '<a href="{{ route("invoice-plp-delete",'') }}/'+cl+'" onclick="if (confirm(\'Are You Sure ?\')){return true; }else{return false; };"><i class="fa fa-close"></i></a>';
                jQuery("#lclInvoicesPlpGrid").jqGrid('setRowData',ids[i],{action:edt+' '+del});
            }

        }

    </script>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">LCL Invoices Lists</h3>
            <div class="box-tools">
                <!--<button class="btn btn-danger btn-sm" id="update-rdm"><i class="fa fa-refresh"></i> UPDATE RDM</button>&nbsp;&nbsp;&nbsp;|||&nbsp;&nbsp;&nbsp;-->
                {{--            <button class="btn btn-info btn-sm" id="cetak-rekap"><i class="fa fa-print"></i> REKAP HARIAN</button>--}}
                <button class="btn btn-warning btn-sm" id="cetak-rekap-akumulasi"><i class="fa fa-print"></i> CREATE PAKET PLP</button>
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
                GridRender::setGridId("lclInvoicesPlpGrid")
                ->enableFilterToolbar()
                ->setGridOption('mtype', 'POST')
                ->setGridOption('url', URL::to('/invoice/paket-plp/grid-data?_token='.csrf_token()))
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
                ->addColumn(array('index'=>'consolidator_id','hidden'=>true))
                ->addColumn(array('label'=>'No. Invoice','index'=>'no_inv','width'=>150,'align'=>'center'))
                ->addColumn(array('label'=>'Consolidator','index'=>'NAMACONSOLIDATOR','width'=>250))
                ->addColumn(array('label'=>'Tanggal<br />Mulai','index'=>'start_date', 'width'=>120, 'align'=>'center'))
                ->addColumn(array('label'=>'Tanggal<br />Akhir','index'=>'end_date', 'width'=>120, 'align'=>'center'))
                ->addColumn(array('label'=>'Jumlah<br />Container 20ft','index'=>'cont_20','width'=>80,'align'=>'center'))
                ->addColumn(array('label'=>'Jumlah<br />Container 40ft','index'=>'cont_40','width'=>80,'align'=>'center'))
                ->addColumn(array('label'=>'No. Container 20ft','index'=>'no_cont_20','width'=>300))
                ->addColumn(array('label'=>'No. Container 40ft','index'=>'no_cont_40','width'=>300))
                ->addColumn(array('label'=>'Total<br />Weight','index'=>'weight', 'width'=>80,'align'=>'center'))
                ->addColumn(array('label'=>'Total<br />Meas','index'=>'meas', 'width'=>80,'align'=>'center'))
                ->addColumn(array('label'=>'Subtotal<br />Amount','index'=>'total','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
                ->addColumn(array('label'=>'PPN 10%','index'=>'ppn','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
                ->addColumn(array('label'=>'Materai','index'=>'materai','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
                ->addColumn(array('label'=>'UID','index'=>'uid', 'width'=>150))
                ->addColumn(array('label'=>'Created Date','index'=>'created_at', 'width'=>160,'align'=>'center'))
                ->renderGrid()
            }}
        </div>
    </div>

    <div id="cetak-rekap-akumulasi-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Create Paket PLP</h4>
                </div>
                <form class="form-horizontal" action="{{ route('invoice-plp-create') }}" method="POST">
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
                                                <option value="{{ $consolidator->id }}">{{ $consolidator->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">No. Invoice</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_invoice" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Tgl. Masuk</label>
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
            jQuery("#lclInvoicesPlpGrid").jqGrid('setGridParam',{url:"{{URL::to('/invoice/grid-data')}}?startdate="+startdate+"&enddate="+enddate}).trigger("reloadGrid");
            return false;
        });

        $('#cetak-rekap-akumulasi').on('click', function(){
            $('#cetak-rekap-akumulasi-modal').modal('show');
        });

        $.fn.bootstrapSwitch.defaults.onColor = 'danger';
        $.fn.bootstrapSwitch.defaults.onText = 'Yes';
        $.fn.bootstrapSwitch.defaults.offText = 'No';
        $("input[type='checkbox']").bootstrapSwitch();
    </script>

@endsection