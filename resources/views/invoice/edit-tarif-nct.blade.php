@extends('layout')

@section('content')

    @include('partials.form-alert')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Form Edit {{ $item->description }}</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <form class="form-horizontal" action="{{ route('invoice-tarif-nct-update', $tarif->id) }}" method="POST">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Lokasi Sandar</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" id="lokasi_sandar" name="lokasi_sandar" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    <option value="">Choose Lokasi Sandar</option>
                                    @foreach($lokasisandar as $ls)
                                        <option value="{{ $ls->KD_TPS_ASAL }}" @if($tarif->lokasi_sandar == $ls->KD_TPS_ASAL) selected @endif>{{ $ls->NAMALOKASISANDAR.' ('.$ls->KD_TPS_ASAL.')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Type</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" id="type" name="type" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                    <option value="">Choose Type</option>
                                    <option value="BB" @if($tarif->type == 'BB') {{'selected'}} @endif>BB</option>
                                    <option value="DRY" @if($tarif->type == 'DRY') {{'selected'}} @endif>DRY</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Size</label>
                            <div class="col-sm-8">
                                <input type="number" name="size" class="form-control" value="{{ $tarif->size }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Masa 1</label>
                            <div class="col-sm-8">
                                <input type="number" name="masa1" class="form-control" value="{{ $tarif->masa1 }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Masa 2</label>
                            <div class="col-sm-8">
                                <input type="number" name="masa2" class="form-control" value="{{ $tarif->masa2 }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Masa 3</label>
                            <div class="col-sm-8">
                                <input type="number" name="masa3" class="form-control" value="{{ $tarif->masa3 }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Masa 4</label>
                            <div class="col-sm-8">
                                <input type="number" name="masa4" class="form-control" value="{{ $tarif->masa4 }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Lift On</label>
                            <div class="col-sm-8">
                                <input type="number" name="lift_on" class="form-control" value="{{ $tarif->lift_on }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Lift Off</label>
                            <div class="col-sm-8">
                                <input type="number" name="lift_off" class="form-control" value="{{ $tarif->lift_off }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Pass Truck</label>
                            <div class="col-sm-8">
                                <input type="number" name="pass_truck" class="form-control" value="{{ $tarif->pass_truck }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Gate Pass Admin</label>
                            <div class="col-sm-8">
                                <input type="number" name="gate_pass_admin" class="form-control" value="{{ $tarif->gate_pass_admin }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Surcharge</label>
                            <div class="col-sm-8">
                                <input type="number" name="surchage" class="form-control" value="{{ $tarif->surchage }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Paket PLP</label>
                            <div class="col-sm-8">
                                <input type="number" name="paket_plp" class="form-control" value="{{ $tarif->paket_plp }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Behandle</label>
                            <div class="col-sm-8">
                                <input type="number" name="behandle" class="form-control" value="{{ $tarif->behandle }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="{{ route("invoice-tarif-nct-index") }}" class="btn btn-danger pull-right" style="margin-right: 10px;"><i class="fa fa-close"></i> Keluar</a>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>

@endsection

@section('custom_css')

    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/datepicker/datepicker3.css") }}">

@endsection

@section('custom_js')

    <script src="{{ asset("/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js") }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
    <script type="text/javascript">
        $('select').select2();
        $('.datepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
    </script>

@endsection