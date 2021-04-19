@extends('print')

@section('title')
    {{ 'Invoice LCL' }}
@stop

@section('content')
    <style>
        @media print {
            body {
                background: #FFF;
                font-weight: bold;
                color: #000;
            }
            table {
                font-weight: bold;
                color: #000;
            }
            @page {
                size: auto;   /* auto is the initial value */
                /*            margin-top: 114px;
                            margin-bottom: 90px;
                            margin-left: 38px;
                            margin-right: 75px;*/
                font-weight: bold;
            }
            .print-btn {
                display: none;
            }
        }
    </style>

    <a href="#" class="print-btn" type="button" onclick="window.print();">PRINT</a>

    <div id="details">
        <div class="row invoice-info">

            <div class="col-sm-4 invoice-col">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2" style="width: 40%">
                            KEPADA<br />
                            <b>{{$consolidator->NAMACONSOLIDATOR}}</b><br />
                            {{$consolidator->ALAMAT}}<br />
                            {{$consolidator->NPWP}}
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="right" style="text-align: right;">
                            No. Invoice : {{ $invoice->no_inv }}<br />
                            Date {{ ($invoice->print_date != "") ? date("d F Y", strtotime($invoice->print_date)) : date("d F Y") }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 100px;"><b>Container 20 ft</b></td>
                        <td style="width: 50%;">:&nbsp;&nbsp;&nbsp;&nbsp;{{ $invoice->no_cont_20 }}</td>
                    </tr>
                    <tr>
                        <td><b>Container 40 ft</b></td>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $invoice->no_cont_40 }}</td>
                    </tr>
                    <tr>
                        <td><b>Total Weight</b></td>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $invoice->weight }} KGS</td>
                    </tr>
                    <tr>
                        <td><b>Total Meas</b></td>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $invoice->meas }} CBM</td>
                    </tr>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <br />
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th>Uraian Jasa</th>
                        <th style="width: 60px;">Cont 20 ft</th>
                        <th style="width: 60px;">Cont 40 ft</th>
                        <th style="width: 100px;text-align: right;">Tarif 20 ft (IDR)</th>
                        <th style="width: 100px;text-align: right;">Tarif 40 ft (IDR)</th>
                        <th style="width: 100px;text-align: right;">Total Biaya (IDR)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoice_items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td style="text-align: center;">{{ $item->cont_20 }}</td>
                            <td style="text-align: center;">{{ $item->cont_40 }}</td>
                            <td style="text-align: right;">{{ number_format($item->rates_20) }}</td>
                            <td style="text-align: right;">{{ number_format($item->rates_40) }}</td>
                            <td style="text-align: right;">{{ number_format($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3">Terbilang:</td>

                        <td style="text-align: right;">Subtotal</td>
                        <td style="text-align: right;">Rp.</td>
                        <td style="text-align: right;">{{ number_format($invoice->total) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="dotted" style="font-size: 9px;"><i>{{ $terbilang }}</i></td>
                        <td style="text-align: right;">PPN 10%</td>
                        <td style="text-align: right;">Rp.</td>
                        <td style="text-align: right;">{{ number_format($invoice->ppn) }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="text-align: right;">Materai</td>
                        <td style="text-align: right;">Rp.</td>
                        <td style="text-align: right;">{{ number_format($invoice->materai) }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="text-align: right;"><b>TOTAL</b></td>
                        <td style="text-align: right;"><b>Rp.</b></td>
                        <td style="text-align: right;"><b>{{ number_format($invoice->total + $invoice->ppn + $invoice->materai) }}</b></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


        <table border="0" cellspacing="0" cellpadding="0">
            <tr><td height="30" style="font-size: 30px;line-height: 0;">&nbsp;</td></tr>
            <tr>
                <td><span style="text-decoration: underline;">PERHATIAN</span></td>
                <td class="text-center">PT. Wira Mitra Prima</td>
            </tr>
            <tr>
                <td width='60%'>
                    <ol>
                        <li>Batas waktu klaim maksimal 3 hari kerja</li>
                        <li>Pembayaran dengan cek/giro dianggap sah setelah cek/giro tersebut diuangkan.</li>
                    </ol>
                    <span><b>Pembayaran Melalui Rekening Bank an. PT. WIRA MITRA PRIMA</b></span><br />
                    <span><b>BCA Cabang Enggano Tj. Priok : 007.303.7601</b></span><br />
                    <span><b>Bank Cab. Kelapa Gading Boulevard : 125 000 486 1415</b></span>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td class="text-center">Yeni Maria</td>
            </tr>
        </table>
    </div>

@stop