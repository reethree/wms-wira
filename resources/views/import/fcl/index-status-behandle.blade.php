@extends('layout')

@section('content')
<style>
    .bootstrap-timepicker-widget {
        left: 27%;
    }
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
    
    function gridCompleteEvent()
    {
        var ids = jQuery("#fclBehandleGrid").jqGrid('getDataIDs'),
            apv = '', chk = '', info = '', vi = '';   
            
        for(var i=0;i < ids.length;i++){ 
            var cl = ids[i];
            
            rowdata = $('#fclBehandleGrid').getRowData(cl); 
            
            if(rowdata.status_behandle == 'Siap Periksa' || rowdata.status_behandle == 'Ready') {
                apv = '';
                chk = '<button style="margin:5px;" class="btn btn-warning btn-xs" data-id="'+cl+'" onclick="if (confirm(\'Apakah anda yakin akan melakukan pemeriksaan ?\')){ changeStatusBehandle('+cl+',\'check\'); }else{return false;};"><i class="fa fa-check"></i> PERIKSA</button>';
            }else if(rowdata.status_behandle == 'Sedang Periksa' || rowdata.status_behandle == 'Checking') {
                apv = '<button style="margin:5px;" class="btn btn-info btn-xs" data-id="'+cl+'" onclick="if (confirm(\'Apakah anda yakin telah selesai melakukan pemeriksaan ?\')){ changeStatusBehandle('+cl+',\'finish\'); }else{return false;};"><i class="fa fa-check"></i> SELESAI</button>';
                chk = '';
            }else if(rowdata.status_behandle == 'Selesai Periksa' || rowdata.status_behandle == 'Finish') {
                apv = '';
                chk = '';
            }else{
                apv = '';
                chk = '';
            }  
            
            if(rowdata.status_behandle == 'Belum Siap') {
                $("#" + cl).find("td").css("background-color", "#FF0000").css("color", "#FFF");
            } 
            if(rowdata.status_behandle == 'Siap Periksa') {
                $("#" + cl).find("td").css("background-color", "#aae25a");
            }
            if(rowdata.status_behandle == 'Sedang Periksa') {
                $("#" + cl).find("td").css("background-color", "#f4dc27");
            }
            if(rowdata.status_behandle == 'Selesai Periksa') {
                $("#" + cl).find("td").css("background-color", "#6acaf7");
            }    
            if(rowdata.status_behandle == 'Delivery') {
                $("#" + cl).find("td").css("background-color", "#ffdc60");
            }
            
            if(rowdata.status_bc == 'HOLD') {
                $("#" + cl).find("td").css("background-color", "#ffe500");
            }
            
            if(rowdata.flag_bc == 'Y') {
                $("#" + cl).find("td").css("background-color", "#d73925").css("color", "#FFF");
            } 
            
            if(rowdata.photo_behandle != '' || rowdata.dokumen_percepatan != ''){
                vi = '<button style="margin:5px;" class="btn btn-default btn-xs approve-manifest-btn" data-id="'+cl+'" onclick="viewPhoto('+cl+')"><i class="fa fa-photo"></i> View</button>';
            }else{
                vi = '<button style="margin:5px;" class="btn btn-default btn-xs approve-manifest-btn" disabled><i class="fa fa-photo"></i> Not Found</button>';
            }
            
            @if(Auth::getUser()->username == 'bcppc3')          
                jQuery("#fclBehandleGrid").jqGrid('setRowData',ids[i],{action:apv+' '+chk,photo:vi});
            @else
                jQuery("#fclBehandleGrid").jqGrid('setRowData',ids[i],{photo:vi});
            @endif
            
        } 
    }
    
    function viewPhoto(containerID)
    {       
        $.ajax({
            type: 'GET',
            dataType : 'json',
            url: '{{route("fcl-report-rekap-view-photo","")}}/'+containerID,
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Something went wrong, please try again later.');
            },
            beforeSend:function()
            {
                $('#container-photo').html('');
                $('#dok-behandle').html('');
            },
            success:function(json)
            {
                var html_container = '';
                var html_behandle = '';
                
                if(json.data.photo_behandle){
                    var photos_container = $.parseJSON(json.data.photo_behandle);
                    var html_container = '';
                    $.each(photos_container, function(i, item) {
                        /// do stuff
                        html_container += '<img src="{{url("uploads/photos/container/fcl")}}/'+json.data.NOCONTAINER+'/'+item+'" style="width: 200px;padding:5px;" />';

                    });
                    $('#container-photo').html(html_container);
                }
                if(json.data.dokumen_percepatan){
                    var dok_behandle = $.parseJSON(json.data.dokumen_percepatan);
                    var html_behandle = '';
                    $.each(dok_behandle, function(i, item) {
                        /// do stuff
                        html_behandle += 'Waktu Percepatan : '+json.data.waktu_percepatan+'<br /><img src="{{url("uploads/behandle/fcl")}}/'+item+'" style="width: 200px;padding:5px;" />';

                    });
                    $('#dok-behandle').html(html_behandle);
                }
                
                $("#title-photo").html('INFO CONTAINER NO. '+json.data.NOCONTAINER);
            }
        });
        
        $('#view-photo-modal').modal('show');
    }
    
    function changeStatusBehandle($id,$action)
    {
        if($action == 'check'){
            $("#container_check_id").val($id);
            $('#check-modal').modal('show');
        }else{
            $("#container_finish_id").val($id);
            $('#finish-modal').modal('show');
        }
    }
    
    $(document).ready(function(){
        
        $("#btn-percepatan").on("click", function(){
            var rowid = $('#fclBehandleGrid').jqGrid('getGridParam', 'selrow');
            var rowdata = $('#fclBehandleGrid').getRowData(rowid);
            
            if(!rowid) {alert('Silahkan pilih salah satu data.');return false;} 
            
            $("#nocont-percepatan").html(rowdata.NOCONTAINER);
            $("#container_percepatan_id").val(rowdata.TCONTAINER_PK);
            $('#percepatan-modal').modal('show');            
        });
        
    });
</script>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">FCL Status Behandle</h3>
        @if(Auth::getUser()->username == 'bcppc3') 
        <div class="box-tools" id="btn-toolbar">
            <div id="btn-group-4">
                <button class="btn btn-danger btn-sm" id="btn-percepatan"><i class="fa fa-fast-forward"></i> PERCEPATAN</button>
            </div>
        </div>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12"> 
                {{
                    GridRender::setGridId("fclBehandleGrid")
                    ->enableFilterToolbar()
                    ->setGridOption('mtype', 'POST')
                    ->setGridOption('url', URL::to('/container/grid-data-cy?module=status_behandle&_token='.csrf_token()))
//                    ->setGridOption('editurl',URL::to('/container/crud-cy/'))
                    ->setGridOption('rowNum', 100)
                    ->setGridOption('shrinkToFit', true)
                    ->setGridOption('sortname','TCONTAINER_PK')
                    ->setGridOption('rownumbers', true)
                    ->setGridOption('rownumWidth', 50)
                    ->setGridOption('height', '395')
                    ->setGridOption('rowList',array(100,200,300))
                    ->setGridOption('useColSpanStyle', true)
                    ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
                    ->setNavigatorOptions('view',array('closeOnEscape'=>false))
                    ->setNavigatorOptions('navigator', array('add' => false, 'edit' => false, 'del' => false, 'view' => true, 'refresh' => false))
                    ->setFilterToolbarOptions(array('autosearch'=>true))
                    ->setGridEvent('gridComplete', 'gridCompleteEvent')
//                    ->setGridEvent('onSelectRow', 'onSelectRowEvent')
                    ->addColumn(array('key'=>true,'index'=>'TCONTAINER_PK','hidden'=>true))
                    ->addColumn(array('label'=>'Info','index'=>'photo', 'width'=>120, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
                    ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>120, 'search'=>false, 'sortable'=>false, 'align'=>'center'))  
                    ->addColumn(array('label'=>'Status Behandle','index'=>'status_behandle','width'=>120, 'align'=>'center'))
                    ->addColumn(array('label'=>'No. SPJM','index'=>'NO_SPJM', 'width'=>120,'hidden'=>false,'align'=>'center'))
                    ->addColumn(array('label'=>'Tgl. SPJM','index'=>'TGL_SPJM', 'width'=>150,'hidden'=>false,'align'=>'center'))
                    ->addColumn(array('label'=>'No. Container','index'=>'NOCONTAINER','width'=>160,'editable' => true, 'editrules' => array('required' => true)))
                    ->addColumn(array('label'=>'Vessel','index'=>'VESSEL', 'width'=>150))
                    ->addColumn(array('label'=>'TPS Asal','index'=>'KD_TPS_ASAL', 'width'=>80,'align'=>'center'))
                    ->addColumn(array('label'=>'Consignee','index'=>'CONSIGNEE','width'=>160,'hidden'=>false))
                    ->addColumn(array('label'=>'No. BC11','index'=>'NO_BC11','width'=>150,'align'=>'right','hidden'=>false))
                    ->addColumn(array('label'=>'Tgl. BC11','index'=>'TGL_BC11','width'=>150,'align'=>'center','hidden'=>false))
                    ->addColumn(array('label'=>'No. POS BC11','index'=>'NO_POS_BC11','width'=>150,'align'=>'center','hidden'=>false))
                    ->addColumn(array('label'=>'No. PLP','index'=>'NO_PLP','width'=>150,'align'=>'right','hidden'=>false))
                    ->addColumn(array('label'=>'Tgl. PLP','index'=>'TGL_PLP','width'=>150,'align'=>'center','hidden'=>false))
                    ->addColumn(array('label'=>'Tgl. Masuk','index'=>'TGLMASUK','width'=>120,'align'=>'center'))
                    ->addColumn(array('label'=>'Jam Masuk','index'=>'JAMMASUK','width'=>120,'align'=>'center'))
                    ->addColumn(array('label'=>'Nama Dokumen','index'=>'KODE_DOKUMEN', 'width'=>120))
                    ->addColumn(array('label'=>'No. SPPB','index'=>'NO_SPPB', 'width'=>120,'align'=>'center'))
                    ->addColumn(array('label'=>'Tgl. SPPB','index'=>'TGL_SPPB', 'width'=>120,'align'=>'center'))
                    ->addColumn(array('label'=>'No. Pabean','index'=>'NO_DAFTAR_PABEAN','width'=>160))
                    ->addColumn(array('label'=>'Tgl. Pabean','index'=>'TGL_DAFTAR_PABEAN', 'width'=>150,'hidden'=>false, 'align'=>'center'))
                    ->addColumn(array('label'=>'Status BC','index'=>'status_bc', 'width'=>80,'align'=>'center'))
                    ->addColumn(array('label'=>'Tgl. Behandle','index'=>'TGLBEHANDLE','width'=>120,'align'=>'center'))
                    ->addColumn(array('label'=>'Jam. Behandle','index'=>'JAMBEHANDLE', 'width'=>120,'align'=>'center'))  
                    ->addColumn(array('label'=>'Segel Merah','index'=>'flag_bc', 'width'=>100,'align'=>'center'))
                    ->addColumn(array('label'=>'No. Segel','index'=>'no_flag_bc','width'=>100,'align'=>'center','hidden'=>false))
                    ->addColumn(array('label'=>'Alasan Segel','index'=>'alasan_segel','width'=>150,'align'=>'center'))
                    ->addColumn(array('label'=>'Photo Behandle','index'=>'photo_behandle', 'width'=>70,'hidden'=>true))
                    ->addColumn(array('label'=>'Dok Percepatan','index'=>'dokumen_percepatan', 'width'=>70,'hidden'=>true))
                    ->addColumn(array('label'=>'UID','index'=>'UID', 'width'=>150,'align'=>'center', 'hidden'=>true))
                    ->addColumn(array('label'=>'Tgl. Entry','index'=>'TGLENTRY', 'width'=>150, 'search'=>false, 'hidden'=>true))
                    ->addColumn(array('label'=>'Jam. Entry','index'=>'JAMENTRY', 'width'=>150, 'search'=>false, 'hidden'=>true))
                    
//                    ->addColumn(array('label'=>'No. SPK','index'=>'NoJob','width'=>160))
//                    ->addColumn(array('label'=>'Size','index'=>'SIZE', 'width'=>80,'align'=>'center','editable' => true, 'editrules' => array('required' => true,'number'=>true),'edittype'=>'select','editoptions'=>array('value'=>"20:20;40:40")))
//                    ->addColumn(array('label'=>'Callsign','index'=>'CALLSIGN', 'width'=>150,'align'=>'center'))
//                    ->addColumn(array('label'=>'Voy','index'=>'VOY','width'=>80,'align'=>'center'))
//                    ->addColumn(array('label'=>'ETA','index'=>'ETA', 'width'=>150,'align'=>'center'))
//                    ->addColumn(array('label'=>'No. MBL','index'=>'NOMBL','width'=>160,'hidden'=>true))
//                    ->addColumn(array('label'=>'Tgl. MBL','index'=>'TGLMBL','width'=>150,'align'=>'center','hidden'=>true))
//                    ->addColumn(array('label'=>'Consolidator','index'=>'NAMACONSOLIDATOR','width'=>250,'hidden'=>true))
//                    ->addColumn(array('label'=>'Importir','index'=>'NAMA_IMP','width'=>160,'hidden'=>true))
//                    ->addColumn(array('label'=>'NPWP Importir','index'=>'NPWP_IMP','width'=>160,'hidden'=>true))
//                    ->addColumn(array('label'=>'Teus','index'=>'TEUS', 'width'=>80,'align'=>'center','editable' => false,'hidden'=>true))
//                    ->addColumn(array('label'=>'No. Seal','index'=>'NOSEGEL', 'width'=>120,'editable' => true, 'align'=>'right','hidden'=>true))
//                    ->addColumn(array('label'=>'Weight','index'=>'WEIGHT', 'width'=>120,'editable' => true, 'align'=>'right','editrules' => array('required' => true),'hidden'=>true))
//                    ->addColumn(array('label'=>'Measurment','index'=>'MEAS', 'width'=>120,'editable' => true, 'align'=>'right','editrules' => array('required' => true),'hidden'=>true))
//                    ->addColumn(array('label'=>'Layout','index'=>'layout', 'width'=>80,'editable' => true,'align'=>'center','editoptions'=>array('defaultValue'=>"C-1"),'hidden'=>true))                   
//                    ->addColumn(array('label'=>'Updated','index'=>'last_update', 'width'=>150, 'search'=>false,'hidden'=>true))
                    ->renderGrid()
                }}
            
        </div>   
    </div>
</div>

<div id="check-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Checking Behandle</h4>
            </div>
            <form id="create-invoice-form" class="form-horizontal" action="{{ route('fcl-change-status-behandle') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            <input name="id" type="hidden" id="container_check_id" />
                            <input name="status_behandle" type="hidden" id="status_behandle" value="Sedang Periksa" />
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Keterangan</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="desc"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Periksa</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="finish-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Finish Behandle</h4>
            </div>
            <form id="create-invoice-form" class="form-horizontal" action="{{ route('fcl-change-status-behandle') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            <input name="id" type="hidden" id="container_finish_id" />
                            <input name="status_behandle" type="hidden" id="status_behandle" value="Selesai Periksa" />
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Keterangan</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="desc"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Selesai</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="view-photo-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="title-photo">Photo</h4>
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <h4>PHOTO BEHANDLE</h4>
                        <div id="container-photo"></div>
                        <hr />
                        <h4>PERCEPATAN</h4>
                        <div id="dok-behandle"></div>
                    </div>
                </div>
            </div>    
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="percepatan-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Percepatan Container No. <span id="nocont-percepatan"></span></h4>
            </div>
            <form id="create-invoice-form" class="form-horizontal" action="{{route('fcl-percepatan-behandle')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            <input name="id" type="hidden" id="container_percepatan_id" />
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tgl. Percepatan</label>
                                <div class="col-sm-8">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" id="tgl_percepatan_behandle" name="tgl_percepatan_behandle" class="form-control pull-right datepicker" required>
                                    </div>
                                </div>
                            </div>
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Jam Percepatan</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" id="jam_percepatan_behandle" name="jam_percepatan_behandle" class="form-control timepicker" required>
                                            <div class="input-group-addon">
                                                  <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Dokumen</label>
                                <div class="col-sm-8">
                                    <input type="file" name="dokumen_percepatan_behandle[]" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
@section('custom_css')

<!--<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />-->
<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/datepicker/datepicker3.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/timepicker/bootstrap-timepicker.min.css") }}">

@endsection
@section('custom_js')

<script src="{{ asset("/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js") }}"></script>
<script src="{{ asset("/bower_components/AdminLTE/plugins/timepicker/bootstrap-timepicker.min.js") }}"></script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>-->
<script type="text/javascript">
//    $('.select2').select2();

    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd' 
    });
    $('.timepicker').timepicker({ 
        showMeridian: false,
        showInputs: false,
        showSeconds: true,
        minuteStep: 1,
        secondStep: 1
    });
    $(".timepicker").mask("99:99:99");
    $(".datepicker").mask("9999-99-99");
</script>

@endsection