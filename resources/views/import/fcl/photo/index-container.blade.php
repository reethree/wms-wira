@extends('layout')

@section('content')
<script>
    
    function onSelectRowEvent()
    {
        $('#btn-view-photo').enableButtonGroup();
        var rowid = $('#fclPhotoContainerGrid').jqGrid('getGridParam', 'selrow');
        var rowdata = $('#fclPhotoContainerGrid').getRowData(rowid);

        $("#id_cont").val(rowdata.TCONTAINER_PK);
        $("#upload-title").html("Upload Photo Container NO. "+rowdata.NOCONTAINER);
    }
    
    function readURL(input,tagid) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#'+tagid)
                    .attr('src', e.target.result)
                    .css('display','block');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $(document).ready(function(){
        $('img').attr('src', '#').css('display','none');
//        $('#btn-view-photo').click(function() {
//            var rowid = $('#fclPhotoContainerGrid').jqGrid('getGridParam', 'selrow');
//            if(rowid){
//                var rowdata = $('#fclPhotoContainerGrid').getRowData(rowid);
//                
//                if(rowdata.photo_gatein_extra){
//                var html = '';
//                var photos = $.parseJSON(rowdata.photo_gatein_extra);
//                $.each(photos, function(i, item) {
//                    /// do stuff
//                    html += '<img src="{{url("uploads/photos/container/fcl/")}}/'+rowdata.NOCONTAINER+'/'+item+'" style="width: 200px;padding:5px;" />';
//                });
//                $('#load_photos').html(html);
//            }
//            }else{
//                alert('Container belum dipilih.');
//            }
//        });
        
    });
</script>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">FCL Photo Container</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12"> 
                {{
                    GridRender::setGridId("fclPhotoContainerGrid")
                    ->enableFilterToolbar()
                    ->setGridOption('mtype', 'POST')
                    ->setGridOption('url', URL::to('/fcl/register/grid-data?module=gatein&_token='.csrf_token()))
                    ->setGridOption('rowNum', 100)
                    ->setGridOption('shrinkToFit', true)
                    ->setGridOption('sortname','TCONTAINER_PK')
                    ->setGridOption('sortorder','DESC')
                    ->setGridOption('rownumbers', true)
                    ->setGridOption('rownumWidth', 50)
                    ->setGridOption('height', '350')
                    ->setGridOption('rowList',array(100,200,500))
                    ->setGridOption('useColSpanStyle', true)
                    ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
                    ->setNavigatorOptions('view',array('closeOnEscape'=>false))
                    ->setFilterToolbarOptions(array('autosearch'=>true))
//                    ->setGridEvent('gridComplete', 'gridCompleteEvent')
                    ->setGridEvent('onSelectRow', 'onSelectRowEvent')
        //            ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
                    ->addColumn(array('key'=>true,'index'=>'TCONTAINER_PK','hidden'=>true))
//                    ->addColumn(array('label'=>'Photo','index'=>'action', 'width'=>120, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
                    ->addColumn(array('label'=>'No. Container','index'=>'NOCONTAINER','width'=>150))
                    ->addColumn(array('label'=>'Jenis Container','index'=>'jenis_container','width'=>150, 'align'=>'center'))
                    ->addColumn(array('label'=>'Size','index'=>'SIZE', 'width'=>80,'align'=>'center'))
                    ->addColumn(array('label'=>'Vessel','index'=>'VESSEL', 'width'=>150))
                    ->addColumn(array('label'=>'Callsign','index'=>'CALLSIGN', 'width'=>150,'align'=>'center'))
                    ->addColumn(array('label'=>'Voy','index'=>'VOY','width'=>80,'align'=>'center'))
                    ->addColumn(array('label'=>'Tujuan','index'=>'GUDANG_TUJUAN', 'width'=>80,'align'=>'center'))
                    ->addColumn(array('label'=>'Tgl. ETA','index'=>'ETA','width'=>120,'align'=>'center'))
                    ->addColumn(array('label'=>'TPS Asal','index'=>'KD_TPS_ASAL', 'width'=>80,'align'=>'center'))
                    ->addColumn(array('index'=>'TCONSOLIDATOR_FK','hidden'=>true))
                    ->addColumn(array('label'=>'Consolidator','index'=>'NAMACONSOLIDATOR','width'=>250,'hidden'=>true))
                    ->addColumn(array('label'=>'No. BC11','index'=>'NO_BC11','width'=>120,'align'=>'center','hidden'=>false))
                    ->addColumn(array('label'=>'Tgl. BC11','index'=>'TGL_BC11','width'=>120,'align'=>'center','hidden'=>false))
                    ->addColumn(array('label'=>'No. PLP','index'=>'NO_PLP','width'=>120,'align'=>'center','hidden'=>false))
                    ->addColumn(array('label'=>'Tgl. PLP','index'=>'TGL_PLP','width'=>120,'align'=>'center','hidden'=>false))
                    
                    ->addColumn(array('index'=>'location_id', 'width'=>150,'hidden'=>true))
                    ->addColumn(array('label'=>'Location','index'=>'location_name','width'=>200, 'align'=>'center','hidden'=>true))
                    
        //            ->addColumn(array('label'=>'Teus','index'=>'TEUS', 'width'=>80,'align'=>'center'))
                    ->addColumn(array('label'=>'No. Seal','index'=>'NO_SEAL', 'width'=>120,'align'=>'right','hidden'=>true))
//                    ->addColumn(array('label'=>'Tgl. Masuk','index'=>'TGLMASUK','width'=>120,'align'=>'center'))
//                    ->addColumn(array('label'=>'Jam Masuk','index'=>'JAMMASUK','width'=>120,'align'=>'center'))
//                    ->addColumn(array('label'=>'Tgl. Keluar TPK','index'=>'TGLKELUAR_TPK','hidden'=>false,'align'=>'center'))
//                    ->addColumn(array('label'=>'Jam Keluar TPK','index'=>'JAMKELUAR_TPK','hidden'=>false,'align'=>'center'))
//                    ->addColumn(array('label'=>'Perkiraan Keluar','index'=>'P_TGLKELUAR','hidden'=>true))
//                    ->addColumn(array('label'=>'Petugas','index'=>'UIDMASUK','hidden'=>true))
//                    ->addColumn(array('label'=>'No. POL','index'=>'NOPOL','hidden'=>false,'align'=>'center'))
//                    ->addColumn(array('label'=>'No. SP2','index'=>'NO_SP2','width'=>120,'hidden'=>true))
//                    ->addColumn(array('label'=>'Tgl. SP2','index'=>'TGL_SP2','hidden'=>true))
//                    ->addColumn(array('label'=>'E-Seal','index'=>'ESEALCODE','hidden'=>true))
//                    ->addColumn(array('label'=>'Weight','index'=>'WEIGHT','hidden'=>false))
                    ->addColumn(array('label'=>'Photo Extra','index'=>'photo_gatein_extra', 'width'=>70,'hidden'=>true))
                    ->addColumn(array('label'=>'Photo Release Extra','index'=>'photo_release_extra', 'width'=>70,'hidden'=>true))
                    ->addColumn(array('label'=>'Photo Behandle','index'=>'photo_behandle', 'width'=>70,'hidden'=>true))
//                    ->addColumn(array('label'=>'Segel Merah','index'=>'flag_bc','width'=>80, 'align'=>'center'))
//                    ->addColumn(array('label'=>'Alasan Segel','index'=>'alasan_segel','width'=>150,'align'=>'center'))
        //            ->addColumn(array('label'=>'UID','index'=>'UID', 'width'=>150))
//                    ->addColumn(array('label'=>'Tgl. Entry','index'=>'TGLENTRY', 'width'=>150,'align'=>'center'))
//                    ->addColumn(array('label'=>'Updated','index'=>'last_update', 'width'=>150, 'search'=>false))
        //            ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
                    ->renderGrid()
                }}
                <div id="btn-toolbar" class="section-header btn-toolbar" role="toolbar" style="margin: 10px 0;">
                    <div id="btn-group-1" class="btn-group">
                        <button class="btn btn-info" id="upload-photo-btn"><i class="fa fa-photo"></i> &nbsp;&nbsp;UPLOAD PHOTO</button>
                    </div>
                </div>
            </div>
                
        </div>
<!--        <form method="post" action="{{url('dropzone/store')}}" enctype="multipart/form-data" class="dropzone" id="dropzone1">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
        </form>-->
    </div>
</div>

<div id="photo-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="modal">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="upload-title"></h4>
            </div>
            <form class="form-horizontal" id="upload-photo-form" action="{{ route('fcl-container-upload-photo') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <input type="hidden" id="id_cont" name="id_cont" required>   
                            <input type="hidden" id="type" name="type" value="fcl" required>      
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Jenis Kegiatan</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="kegiatan" name="kegiatan" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="">Pilih Jenis Kegiatan</option>
                                        <option value="gatein">Gate In</option>
                                        <option value="behandle">Behandle</option>
                                        <option value="release">Release</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Photo 1</label>
                                <div class="col-sm-8">
                                    <img id="prevPhoto1" src="#" alt="Photo 1" style="display: none;width: 100%;margin-bottom: 10px;" />
                                    <input type="file" name="photos[]" class="form-control" multiple="false" onchange="readURL(this,'prevPhoto1');">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Photo 2</label>
                                <div class="col-sm-8">
                                    <img id="prevPhoto2" src="#" alt="Photo 2" style="display: none;width: 100%;margin-bottom: 10px;" />
                                    <input type="file" name="photos[]" class="form-control" multiple="false" onchange="readURL(this,'prevPhoto2');">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Photo 3</label>
                                <div class="col-sm-8">
                                    <img id="prevPhoto3" src="#" alt="Photo 3" style="display: none;width: 100%;margin-bottom: 10px;" />
                                    <input type="file" name="photos[]" class="form-control" multiple="false" onchange="readURL(this,'prevPhoto3');">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Photo 4</label>
                                <div class="col-sm-8">
                                    <img id="prevPhoto4" src="#" alt="Photo 4" style="display: none;width: 100%;margin-bottom: 10px;" />
                                    <input type="file" name="photos[]" class="form-control" multiple="false" onchange="readURL(this,'prevPhoto4');">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Hapus Photo Sebelumnya</label>
                                <div class="col-sm-5">
                                    <input type="checkbox" name="hapus" value="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('custom_css')

<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/min/dropzone.min.css">-->
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.css") }}">

@endsection

@section('custom_js')

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/dropzone.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<script src="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.js") }}"></script>

<script type="text/javascript">
    
    $('select').select2();
    
    $.fn.bootstrapSwitch.defaults.onColor = 'danger';
    $.fn.bootstrapSwitch.defaults.onText = 'Yes';
    $.fn.bootstrapSwitch.defaults.offText = 'No';
    $("input[type='checkbox']").bootstrapSwitch();
    
    $("#upload-photo-btn").on("click", function(e){
        e.preventDefault();
        var rowid = $('#fclPhotoContainerGrid').jqGrid('getGridParam', 'selrow');
        if(rowid){
            $('img').attr('src', '#').css('display','none');
            $('input[type=file]').val("");
            $("#photo-modal").modal('show');
        }else{
            alert('Container belum dipilih.');
        }
        return false;
    });
    
//    Dropzone.options.dropzone =
//    {
//        maxFilesize: 5,
//        renameFile: function (file) {
//            var dt = new Date();
//            var time = dt.getTime();
//            return time + file.name;
//        },
//        acceptedFiles: ".jpeg,.jpg,.png",
//        addRemoveLinks: true,
//        timeout: 60000,
//        removedfile: function(file) 
//        {
//            var name = file.upload.filename;
//            $.ajax({
//                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
//                type: 'POST',
//                url: '{{ url("image/delete") }}',
//                data: {filename: name},
//                success: function (data){
//                    console.log("File has been successfully removed!!");
//                },
//                error: function(e) {
//                    console.log(e);
//                }});
//                var fileRef;
//                return (fileRef = file.previewElement) != null ? fileRef.parentNode.removeChild(file.previewElement) : void 0;
//        },
//        success: function (file, response) {
//            console.log(response);
//        },
//        error: function (file, response) {
//            return false;
//        }
//    };
</script>
@endsection