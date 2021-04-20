@extends('layout')

@section('content')
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <b>{{ $consolidator->NAMACONSOLIDATOR }}</b>
                    <small class="pull-right">Date: {{ date('d F, Y') }}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-xs-12 text-center margin-bottom">
                <h2><b>INVOICE</b></h2>
            </div>
            <div class="col-sm-12 invoice-col" style="margin-bottom: 20px;padding: 0">
                <div class="col-sm-5">
                    <p>KEPADA</p>
                    <p>
                        <b>{{$consolidator->NAMACONSOLIDATOR}}</b><br />
                        {{$consolidator->ALAMAT}}<br />
                        {{$consolidator->NPWP}}
                    </p>
                </div>
                <div class="col-sm-7">
                    <p style="text-align: right;">No. Invoice : {{ $invoice->no_inv }}</p>
                    <p style="text-align: right;">Date : {{ date('d F Y', strtotime($invoice->print_date)) }}</p>
                </div>
            </div>
            <div class="col-sm-2 invoice-col">
                <table>
                    <tr>
                        <td><b>No. Container 20 ft</b></td>
                    </tr>
                    <tr>
                        <td><b>No. Container 40 ft</b></td>
                    </tr>
                </table>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 invoice-col">
                <table>
                    <tr>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $invoice->no_cont_20 }}</td>
                    </tr>
                    <tr>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $invoice->no_cont_40 }}</td>
                    </tr>
                </table>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <table>

                    <tr>
                        <td><b>Total Weight</b></td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td>{{ $invoice->weight }} KGS</td>
                    </tr>
                    <tr>
                        <td><b>Total Meas</b></td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td>{{ $invoice->meas }} CBM</td>
                    </tr>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <br /><br />
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped" border="0">
                    <thead>
                    <tr>
                        <th rowspan="2">Uraian Jasa</th>
                        <th colspan="2" style="text-align: center;">Jumlah Container</th>
                        <th colspan="2" style="text-align: center;">Tarif</th>
                        <th rowspan="2">Total Biaya (IDR)</th>
                    </tr>
                    <tr>
                        <th>20 ft</th>
                        <th>40 ft</th>
                        <th>20 ft</th>
                        <th>40 ft</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($invoice_items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->cont_20 }}</td>
                            <td>{{ $item->cont_40 }}</td>
                            <td>{{ number_format($item->rates_20) }}</td>
                            <td>{{ number_format($item->rates_40) }}</td>
                            <td>{{ number_format($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <p>Terbilang:</p>
                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px; color: #000;">
                    {{ $terbilang }}
                </p>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                <!--<p class="lead">Amount Due 2/22/2014</p>-->

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th style="width:50%" align="right">Subtotal:</th>
                            <td align="right">Rp.</td>
                            <td align="right">{{ number_format($invoice->total) }}</td>
                        </tr>
                        <tr>
                            <th align="right">PPN 10%</th>
                            <td align="right">Rp.</td>
                            <td align="right">{{ number_format($invoice->ppn) }}</td>
                        </tr>
                        <tr>
                            <th align="right">Materai</th>
                            <td align="right">Rp.</td>
                            <td align="right">{{ number_format($invoice->materai) }}</td>
                        </tr>
                        <tr>
                            <th align="right">Total:</th>
                            <td align="right"><b>Rp.</b></td>
                            <td align="right"><b>{{ number_format($invoice->total + $invoice->ppn + $invoice->materai) }}</b></td>
                        </tr>
                        </tbody></table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-xs-12">
                <button id="print-invoice-btn" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
                <!--        <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
                        </button>
                        <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                          <i class="fa fa-download"></i> Generate PDF
                        </button>-->
            </div>
        </div>
    </section>

@endsection

@section('custom_css')

@endsection

@section('custom_js')

    <script type="text/javascript">
        $('#print-invoice-btn').click(function() {
            window.open("{{ route('invoice-plp-print',$invoice->id) }}","preview invoice ","width=600,height=600,menubar=no,status=no,scrollbars=yes");
        });
    </script>

@endsection