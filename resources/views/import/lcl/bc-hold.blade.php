@extends('layout')

@section('content')
<script>
 
    function gridCompleteEvent()
    {
        
        var $grid = jQuery('#lclHoldGrid');
        var colweightSum = $grid.jqGrid('getCol', 'WEIGHT', false, 'sum');
        var colmeasSum = $grid.jqGrid('getCol', 'MEAS', false, 'sum');
        
//        $grid.jqGrid('footerData', 'set', { WEIGHT: precisionRound(colweightSum, 4) });
//        $grid.jqGrid('footerData', 'set', { MEAS: precisionRound(colmeasSum, 4) });
        
        var ids = jQuery("#lclHoldGrid").jqGrid('getDataIDs'),
            apv = '', sgl = '', info = '', vi = '';   
            
        for(var i=0;i < ids.length;i++){ 
            var cl = ids[i];
            
            rowdata = $('#lclHoldGrid').getRowData(cl);
            
            if(rowdata.status_bc == 'HOLD') {
                apv = '<button style="margin:5px;" class="btn btn-danger btn-xs" data-id="'+cl+'" onclick="if (confirm(\'Are You Sure to change status HOLD to RELEASE ?\')){ changeStatus('+cl+'); }else{return false;};"><i class="fa fa-check"></i> RELEASE</button>';
            }else{
                apv = '';
            }   
            
//            if(rowdata.perubahan_hbl == 'Y') {
//                $("#" + cl).find("td").css("background-color", "#3dc6f2");
//            }
            if(rowdata.status_bc == 'HOLD') {
                $("#" + cl).find("td").css("background-color", "#ffe500");
            }
            
            if(rowdata.photo_release != ''){
                vi = '<button style="margin:5px;" class="btn btn-default btn-xs" data-id="'+cl+'" onclick="viewPhoto('+cl+')"><i class="fa fa-photo"></i> View Photo</button>';
            }else{
                vi = '<button style="margin:5px;" class="btn btn-default btn-xs" disabled><i class="fa fa-photo"></i> Not Found</button>';
            }
            
            @if(Auth::getUser()->username == 'bcgaters' || Auth::getUser()->username == 'bchanggar') 
                jQuery("#lclHoldGrid").jqGrid('setRowData',ids[i],{photo: vi,hold: apv}); 
            @else
                jQuery("#lclHoldGrid").jqGrid('setRowData',ids[i],{photo: vi}); 
            @endif
        } 
    
    }
    
    function viewPhoto(manifestID)
    {

        $.ajax({
            type: 'GET',
            dataType : 'json',
            url: '{{route("lcl-report-inout-view-photo","")}}/'+manifestID,
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Something went wrong, please try again later.');
            },
            beforeSend:function()
            {
                $('#release-photo').html('');
            },
            success:function(json)
            {
                if(json.data.photo_release){
                    var photos_release = $.parseJSON(json.data.photo_release);
                    var html_release = '';
                    $.each(photos_release, function(i, item) {
                        /// do stuff
                        html_release += '<img src="{{url("uploads/photos/manifest")}}/'+item+'" style="width: 200px;padding:5px;" />';
                    });
                    $('#release-photo').html(html_release);
                }
 
                $("#title-photo").html('PHOTO HBL NO. '+json.data.NOHBL);
            }
        });
        
        $('#view-photo-modal').modal('show');
    }
    
    function changeStatus($id)
    {
        $.ajax({
            type: 'GET',
            dataType : 'json',
            url: "{{ route('lcl-change-status','') }}/"+$id,
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Something went wrong, please try again later.');
            },
            beforeSend:function()
            {

            },
            success:function(json)
            {
                if(json.success) {
                    $('#btn-toolbar').showAlertAfterElement('alert-success alert-custom', json.message, 5000);
                    $('#lclHoldGrid').jqGrid().trigger("reloadGrid");
                } else {
                    $('#btn-toolbar').showAlertAfterElement('alert-danger alert-custom', json.message, 5000);
                }

                //Triggers the "Refresh" button funcionality.
                $('#btn-refresh').click();
            }
        });
    }
    
    function precisionRound(number, precision) {
        var factor = Math.pow(10, precision);
        return Math.round(number * factor) / factor;
    }
    
</script>
<style>
    .datepicker.dropdown-menu {
        z-index: 100 !important;
    }
    .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word; /* IE 5.5+ and CSS3 */
        white-space: pre-wrap; /* CSS3 */
        white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        overflow: hidden;
        height: auto;
        vertical-align: middle;
        padding-top: 3px;
        padding-bottom: 3px
    }
</style>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">LCL Lists Manifest</h3>
<!--        <div class="box-tools">
            <a href="{{ route('lcl-register-create') }}" type="button" class="btn btn-block btn-info btn-sm"><i class="fa fa-plus"></i> Add New</a>
        </div>-->
    </div>
    <div class="box-body table-responsive">
        <div class="row" style="margin-bottom: 30px;margin-right: 0;">
            <div class="col-md-8">
                <div class="col-xs-12">Search By Date</div>
                <div class="col-xs-12">&nbsp;</div>
                <div class="col-xs-3">
                    <select class="form-control select2" id="by" name="by" style="width: 100%;" tabindex="-1" aria-hidden="true">
                        <option value="ETA">ETA</option>
                        <option value="TGL_BC11">Tgl. BC11</option>
                        <option value="tglmasuk">Tgl. GateIn</option>
                        
                    </select>
                </div>
                <div class="col-xs-3">
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
                <div class="col-xs-3">
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
        <div id="btn-toolbar" class="section-header btn-toolbar" role="toolbar" style="margin: 10px 0;"></div>
        {{
            GridRender::setGridId("lclHoldGrid")
            ->enableFilterToolbar()
            ->setGridOption('mtype', 'POST')
            ->setGridOption('url', URL::to('/lcl/manifest/grid-data?module=hold&_token='.csrf_token()))
            ->setGridOption('rowNum', 50)
            ->setGridOption('shrinkToFit', true)
            ->setGridOption('sortname','timeSinceUpdate')
            ->setGridOption('sortorder','DESC')
            ->setGridOption('rownumbers', true)
            ->setGridOption('rownumWidth', 50)
            ->setGridOption('height', '395')
            ->setGridOption('rowList',array(50,100,200,500))
            ->setGridOption('useColSpanStyle', true)
//            ->setGridOption('footerrow', true)
            ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
            ->setNavigatorOptions('view',array('closeOnEscape'=>false))
            ->setFilterToolbarOptions(array('autosearch'=>true))
            ->setGridEvent('gridComplete', 'gridCompleteEvent')
//            ->setGridEvent('onSelectRow', 'onSelectRowEvent')
            ->addColumn(array('key'=>true,'index'=>'TMANIFEST_PK','hidden'=>true))
            ->addColumn(array('label'=>'Photo','index'=>'photo', 'width'=>120, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
            ->addColumn(array('label'=>'Action','index'=>'hold', 'width'=>120, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
            ->addColumn(array('label'=>'Segel Merah','index'=>'flag_bc', 'width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'Status BC','index'=>'status_bc', 'width'=>80,'align'=>'center'))            
            ->addColumn(array('label'=>'Nama Dokumen','index'=>'KODE_DOKUMEN', 'width'=>130))
            ->addColumn(array('label'=>'No. SPPB','index'=>'NO_SPPB', 'width'=>120,'align'=>'center'))
//            ->addColumn(array('label'=>'No. SPK','index'=>'NOJOBORDER', 'width'=>150))
            ->addColumn(array('label'=>'No. HBL','index'=>'NOHBL','width'=>160))
            ->addColumn(array('label'=>'Tgl. HBL','index'=>'TGL_HBL', 'width'=>150,'align'=>'center'))
            ->addColumn(array('label'=>'No. Container','index'=>'NOCONTAINER', 'width'=>150,'align'=>'center'))
            ->addColumn(array('label'=>'Size','index'=>'SIZE', 'width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'Nama Angkut','index'=>'VESSEL','width'=>160))
            ->addColumn(array('label'=>'Call Sign','index'=>'CALL_SIGN','width'=>100,'align'=>'center','hidden'=>true))
            ->addColumn(array('label'=>'VOY','index'=>'VOY','width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'ETA','index'=>'ETA', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Consolidator','index'=>'NAMACONSOLIDATOR','width'=>300))
            ->addColumn(array('label'=>'No. MBL','index'=>'NOMBL','width'=>160))
            ->addColumn(array('label'=>'Tgl. MBL','index'=>'TGL_MASTER_BL', 'width'=>150,'hidden'=>false, 'align'=>'center'))
            ->addColumn(array('label'=>'Shipper','index'=>'SHIPPER','width'=>160,'hidden'=>true))
            ->addColumn(array('label'=>'Consignee','index'=>'CONSIGNEE', 'width'=>300))
            ->addColumn(array('label'=>'Desc of Goods','index'=>'DESCOFGOODS', 'width'=>300,'hidden'=>true))
            ->addColumn(array('label'=>'Qty','index'=>'QUANTITY', 'width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Packing','index'=>'NAMAPACKING', 'width'=>120))
            ->addColumn(array('label'=>'Kode Kemas','index'=>'KODE_KEMAS', 'width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'Weight','index'=>'WEIGHT', 'width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'Meas','index'=>'MEAS', 'width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'No.BC 1.1','index'=>'NO_BC11', 'width'=>150,'align'=>'center'))
            ->addColumn(array('label'=>'Tgl.BC 1.1','index'=>'TGL_BC11', 'width'=>150,'align'=>'center'))
            ->addColumn(array('label'=>'No.POS BC11','index'=>'NO_POS_BC11', 'width'=>150,'align'=>'center'))
            ->addColumn(array('label'=>'Tgl. Gate In','index'=>'tglmasuk', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Jam. Gate In','index'=>'jammasuk', 'width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'Tgl. Stripping','index'=>'tglstripping', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Jam. Stripping','index'=>'jamstripping', 'width'=>100,'align'=>'center'))
            ->addColumn(array('index'=>'location_id', 'width'=>150,'hidden'=>true))
            ->addColumn(array('label'=>'Lokasi','index'=>'location_name','width'=>200, 'align'=>'center'))
            ->addColumn(array('label'=>'No. Segel','index'=>'no_flag_bc','width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'Alasan Segel','index'=>'alasan_segel','width'=>150,'align'=>'center','hidden'=>true))
            ->addColumn(array('label'=>'Perubahan HBL','index'=>'perubahan_hbl','width'=>100, 'align'=>'center','hidden'=>true))
            ->addColumn(array('label'=>'Alasan Perubahan','index'=>'alasan_perubahan','width'=>150,'align'=>'center','hidden'=>true))
            ->addColumn(array('label'=>'Photo Release','index'=>'photo_release', 'width'=>70,'hidden'=>true))
            ->addColumn(array('label'=>'Lama Timbun (Hari)','index'=>'timeSinceUpdate', 'width'=>150, 'search'=>false, 'align'=>'center'))
            ->renderGrid()
        }}
    </div>
</div>
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
                        <h4>RELEASE</h4>
                        <div id="release-photo"></div>
                    </div>
                </div>
            </div>    
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  
@endsection

@section('custom_css')

<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/datepicker/datepicker3.css") }}">

@endsection

@section('custom_js')

<script src="{{ asset("/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js") }}"></script>
<script type="text/javascript">
    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        zIndex: 99
    });
    
//    $('#alasan_segel').on("change", function(){
//        var val = $(this).val();
//        console.log($(this).val());
//        if(val == 'Lainnya'){
//            $("#alasan_lainnya").show();
//            $("textarea[name='alasan_lainnya']").attr("required", "required");
//        }else{
//            $("#alasan_lainnya").hide();
//            $("textarea[name='alasan_lainnya']").removeAttr("required");
//        }
//    });
    
    $('#searchByDateBtn').on("click", function(){
        var by = $("#by").val();
        var startdate = $("#startdate").val();
        var enddate = $("#enddate").val();
        var string_filters = '';
        var filters = '{"groupOp":"AND","rules":[{"field":"'+by+'","op":"ge","data":"'+startdate+'"},{"field":"'+by+'","op":"le","data":"'+enddate+'"}]}';

        var current_filters = jQuery("#lclHoldGrid").getGridParam("postData").filters;
        
        if (current_filters) {
            var get_filters = $.parseJSON(current_filters);
            if (get_filters.rules !== undefined && get_filters.rules.length > 0) {

                var tempData = get_filters.rules;
                
                tempData.push( { "field":by,"op":"ge","data":startdate } );
                tempData.push( { "field":by,"op":"le","data":enddate } );
                
                string_filters = JSON.stringify(tempData);
                
                filters = '{"groupOp":"AND","rules":'+string_filters+'}';
            }
        }
        
//        jQuery("#lclHoldGrid").jqGrid('setGridParam',{url:"{{URL::to('/lcl/manifest/grid-data')}}?startdate="+startdate+"&enddate="+enddate+"&by="+by}).trigger("reloadGrid");
        jQuery("#lclHoldGrid").jqGrid("setGridParam", { postData: {filters} }).trigger("reloadGrid");
        
        return false;
    });
</script>

@endsection