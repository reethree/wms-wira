@extends('layout')

@section('content')

<script>
    
    function onSelectRowEvent()
    {
        $('#btn-view-photo').enableButtonGroup();
        var rowid = $('#lclPhotoCargoGrid').jqGrid('getGridParam', 'selrow');
        var rowdata = $('#lclPhotoCargoGrid').getRowData(rowid);

        $("#id_hbl").val(rowdata.TMANIFEST_PK);
        $("#upload-title").html("Upload Photo HBL NO. "+rowdata.NOHBL);
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
    });
</script>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">LCL Photo Cargo</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12"> 
                {{
                    GridRender::setGridId("lclPhotoCargoGrid")
                    ->enableFilterToolbar()
                    ->setGridOption('mtype', 'POST')
                    ->setGridOption('url', URL::to('/lcl/manifest/grid-data?module=release&_token='.csrf_token()))
                    ->setGridOption('rowNum', 100)
                    ->setGridOption('shrinkToFit', true)
                    ->setGridOption('sortname','TMANIFEST_PK')
                    ->setGridOption('rownumbers', true)
                    ->setGridOption('rownumWidth', 50)
                    ->setGridOption('multiselect', false)
                    ->setGridOption('height', '350')
                    ->setGridOption('rowList',array(100,200,500))
                    ->setGridOption('useColSpanStyle', true)
                    ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
                    ->setNavigatorOptions('view',array('closeOnEscape'=>false))
                    ->setFilterToolbarOptions(array('autosearch'=>true))
//                    ->setGridEvent('gridComplete', 'gridCompleteEvent')
                    ->setGridEvent('onSelectRow', 'onSelectRowEvent')
                    ->addColumn(array('key'=>true,'index'=>'TMANIFEST_PK','hidden'=>true))
//                    ->addColumn(array('label'=>'Photo','index'=>'photo', 'width'=>120, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
//                    ->addColumn(array('label'=>'Status BC','index'=>'status_bc','width'=>100, 'align'=>'center'))
//                    ->addColumn(array('label'=>'Segel Merah','index'=>'flag_bc','width'=>80, 'align'=>'center'))
//                    ->addColumn(array('label'=>'Validasi','index'=>'VALIDASI','width'=>80, 'align'=>'center'))
//                    ->addColumn(array('label'=>'No. SPK','index'=>'NOJOBORDER', 'width'=>150,'hidden'=>false))
                    ->addColumn(array('label'=>'No. Container','index'=>'NOCONTAINER', 'width'=>150,'hidden'=>false))
                    ->addColumn(array('label'=>'Vessel','index'=>'VESSEL','width'=>120,'align'=>'center'))
                    ->addColumn(array('label'=>'TPS Asal','index'=>'KD_TPS_ASAL','width'=>80,'align'=>'center'))
//                    ->addColumn(array('label'=>'No. MBL','index'=>'NOMBL','width'=>160))
//                    ->addColumn(array('label'=>'Tgl. MBL','index'=>'TGL_MASTER_BL', 'width'=>150,'hidden'=>false, 'align'=>'center'))                  
                    ->addColumn(array('label'=>'No. HBL','index'=>'NOHBL','width'=>160))
                    ->addColumn(array('label'=>'Tgl. HBL','index'=>'TGL_HBL', 'width'=>150,'hidden'=>false, 'align'=>'center'))
                    ->addColumn(array('label'=>'No. Tally','index'=>'NOTALLY','width'=>160,'hidden'=>true))                    
                    ->addColumn(array('label'=>'Consolidator','index'=>'NAMACONSOLIDATOR','width'=>250))
                    ->addColumn(array('label'=>'Consignee','index'=>'CONSIGNEE','width'=>250))
                    ->addColumn(array('label'=>'Weight','index'=>'WEIGHT', 'width'=>120, 'align'=>'center'))               
                    ->addColumn(array('label'=>'Meas','index'=>'MEAS', 'width'=>120, 'align'=>'center'))
                    ->addColumn(array('label'=>'Qty','index'=>'QUANTITY', 'width'=>80,'align'=>'center'))
                    ->addColumn(array('label'=>'Packing','index'=>'NAMAPACKING', 'width'=>120, 'align'=>'center'))
                    ->addColumn(array('label'=>'Kode Kemas','index'=>'KODE_KEMAS', 'width'=>100,'align'=>'center'))
                    ->addColumn(array('label'=>'No.PLP','index'=>'NO_PLP', 'width'=>120,'hidden'=>false, 'align'=>'center'))                
                    ->addColumn(array('label'=>'Tgl.PLP','index'=>'TGL_PLP', 'width'=>120,'hidden'=>false, 'align'=>'center'))
                    ->addColumn(array('label'=>'No.BC11','index'=>'NO_BC11', 'width'=>120,'hidden'=>false, 'align'=>'center'))
                    ->addColumn(array('label'=>'Tgl.BC11','index'=>'TGL_BC11', 'width'=>120,'hidden'=>false, 'align'=>'center'))
                    ->addColumn(array('label'=>'No.POS BC11','index'=>'NO_POS_BC11', 'width'=>120, 'align'=>'center'))
                    ->addColumn(array('label'=>'No. SPPB','index'=>'NO_SPPB', 'width'=>120,'hidden'=>false, 'align'=>'center'))
                    ->addColumn(array('label'=>'Tgl. SPPB','index'=>'TGL_SPPB', 'width'=>120,'hidden'=>false, 'align'=>'center'))
                    ->addColumn(array('label'=>'Kode Dokumen','index'=>'KD_DOK_INOUT', 'width'=>120,'hidden'=>false, 'align'=>'center'))
                    ->addColumn(array('label'=>'Nama Dokumen','index'=>'KODE_DOKUMEN', 'width'=>150,'hidden'=>false, 'align'=>'center'))  
//                    ->addColumn(array('label'=>'Tgl. Release','index'=>'tglrelease', 'width'=>120, 'align'=>'center'))
//                    ->addColumn(array('label'=>'Jam. Release','index'=>'jamrelease', 'width'=>120,'hidden'=>false, 'align'=>'center'))
//                    ->addColumn(array('label'=>'No. POL','index'=>'NOPOL_RELEASE', 'width'=>100,'hidden'=>false, 'align'=>'center'))
//                    
//                    ->addColumn(array('label'=>'No. Pabean','index'=>'no_pabean', 'width'=>150, 'align'=>'center','hidden'=>false))
//                    ->addColumn(array('label'=>'Tgl. Pabean','index'=>'tgl_pabean', 'width'=>150, 'align'=>'center','hidden'=>false))
//                    ->addColumn(array('label'=>'No. SPJM','index'=>'NO_SPJM', 'width'=>150,'hidden'=>true))
//                    ->addColumn(array('label'=>'Tgl. SPJM','index'=>'TGL_SPJM', 'width'=>150,'hidden'=>true))
//                    
//                                     
//                    ->addColumn(array('label'=>'Kode Kuitansi','index'=>'NO_KUITANSI', 'width'=>150,'hidden'=>true))
//                    ->addColumn(array('label'=>'Shipper','index'=>'SHIPPER','width'=>160,'hidden'=>true))
//                    ->addColumn(array('label'=>'Notify Party','index'=>'NOTIFYPARTY','width'=>160,'hidden'=>true))
//                    
//                    ->addColumn(array('label'=>'No. Rack','index'=>'RACKING', 'width'=>150,'hidden'=>true)) 
//                    ->addColumn(array('label'=>'UID','index'=>'UID', 'width'=>150,'hidden'=>true))          
//                    ->addColumn(array('index'=>'TSHIPPER_FK', 'width'=>150,'hidden'=>true))
//                    ->addColumn(array('index'=>'TCONSIGNEE_FK', 'width'=>150,'hidden'=>true))
//                    ->addColumn(array('label'=>'Consignee','index'=>'CONSIGNEE', 'width'=>150,'hidden'=>true))
//                    ->addColumn(array('label'=>'NPWP Consignee','index'=>'ID_CONSIGNEE', 'width'=>150,'hidden'=>true))
//                    ->addColumn(array('index'=>'TNOTIFYPARTY_FK', 'width'=>150,'hidden'=>true))
//                    ->addColumn(array('index'=>'TPACKING_FK', 'width'=>150,'hidden'=>true))
//                    ->addColumn(array('label'=>'Marking','index'=>'MARKING', 'width'=>150,'hidden'=>true)) 
//                    ->addColumn(array('label'=>'Desc of Goods','index'=>'DESCOFGOODS', 'width'=>150,'hidden'=>true))              
//                    
//                    ->addColumn(array('label'=>'Tgl.Behandle','index'=>'tglbehandle', 'width'=>150,'hidden'=>true)) 
//                    ->addColumn(array('label'=>'Surcharge (DG)','index'=>'DG_SURCHARGE', 'width'=>150,'hidden'=>true))
//                    ->addColumn(array('label'=>'Surcharge (Weight)','index'=>'WEIGHT_SURCHARGE', 'width'=>150,'hidden'=>true))
//                    
//                    ->addColumn(array('label'=>'Nama EMKL','index'=>'NAMAEMKL', 'width'=>150,'hidden'=>true)) 
//                    ->addColumn(array('label'=>'Telp. EMKL','index'=>'TELPEMKL', 'width'=>150,'hidden'=>true)) 
//                    ->addColumn(array('label'=>'Telp. PPJK','index'=>'telp_ppjk', 'width'=>150,'hidden'=>true))
//                    ->addColumn(array('label'=>'No. Truck','index'=>'NOPOL', 'width'=>150,'hidden'=>true)) 
//                    ->addColumn(array('label'=>'No. POL Release','index'=>'NOPOL_RELEASE', 'width'=>150,'hidden'=>true)) 
//                    ->addColumn(array('label'=>'Tgl. Surat Jalan','index'=>'TGLSURATJALAN', 'width'=>120,'hidden'=>true))
//                    ->addColumn(array('label'=>'Jam. Surat Jalan','index'=>'JAMSURATJALAN', 'width'=>70,'hidden'=>true))
//                    ->addColumn(array('label'=>'Petugas Surat Jalan','index'=>'UIDSURATJALAN', 'width'=>70,'hidden'=>true))
//                    ->addColumn(array('label'=>'Petugas Release','index'=>'UIDRELEASE', 'width'=>120,'hidden'=>true, 'align'=>'center'))
//                    ->addColumn(array('label'=>'Penagihan','index'=>'PENAGIHAN', 'width'=>70,'hidden'=>true))
//                    ->addColumn(array('label'=>'BCF Consignee','index'=>'bcf_consignee', 'width'=>70,'hidden'=>true))
//                    ->addColumn(array('label'=>'Ref Number','index'=>'REF_NUMBER_OUT', 'width'=>70,'hidden'=>true))
//                    ->addColumn(array('label'=>'Tgl. Fiat Muat','index'=>'tglfiat', 'width'=>120,'hidden'=>true))
//                    ->addColumn(array('label'=>'Jam. Fiat Muat','index'=>'jamfiat', 'width'=>70,'hidden'=>true))
//                    ->addColumn(array('label'=>'Tgl. Entry','index'=>'tglentry', 'width'=>120, 'align'=>'center','hidden'=>true))
//                    ->addColumn(array('label'=>'Jam. Entry','index'=>'jamentry', 'width'=>120,'hidden'=>true, 'align'=>'center'))
//                    ->addColumn(array('label'=>'Perubahan HBL','index'=>'perubahan_hbl','width'=>100, 'align'=>'center'))
//                    ->addColumn(array('label'=>'Alasan Perubahan','index'=>'alasan_perubahan','width'=>150,'align'=>'center'))
//                    ->addColumn(array('label'=>'Alasan Segel','index'=>'alasan_segel','width'=>150,'align'=>'center','hidden'=>true))
//                    ->addColumn(array('label'=>'Photo Release','index'=>'photo_release', 'width'=>70,'hidden'=>true))
//                    ->addColumn(array('label'=>'Lokasi Tujuan','index'=>'LOKASI_TUJUAN', 'width'=>70,'hidden'=>true))
//                    ->addColumn(array('label'=>'Updated','index'=>'last_update', 'width'=>150, 'search'=>false,'hidden'=>true))
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
            <form class="form-horizontal" id="upload-photo-form" action="{{ route('lcl-cargo-upload-photo') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <input type="hidden" id="id_hbl" name="id_hbl" required>  
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Jenis Kegiatan</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="kegiatan" name="kegiatan" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="">Pilih Jenis Kegiatan</option>
                                        <option value="stripping">Stripping</option>
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
        var rowid = $('#lclPhotoCargoGrid').jqGrid('getGridParam', 'selrow');
        if(rowid){
            $('img').attr('src', '#').css('display','none');
            $('input[type=file]').val("");
            $("#photo-modal").modal('show');
        }else{
            alert('HBL belum dipilih.');
        }
        return false;
    });

</script>
@endsection