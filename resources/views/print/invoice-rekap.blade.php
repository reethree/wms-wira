@extends('print')

@section('title')
    {{ $consolidator->NAMACONSOLIDATOR.' '.date('d-m-Y') }}
@stop

@section('content')
<style>
    @media print {
        body {
            color: #000;
            background: #fff;
        }
        @page {
            size: auto;   /* auto is the initial value */
            margin-top: 114px;
            margin-bottom: 90px;
            margin-left: 38px;
            margin-right: 75px;
            font-weight: bold;
        }
        .print-btn {
            display: none;
        }
    }
</style>
<!--<br /><br />-->
<a href="#" class="print-btn" type="button" onclick="window.print();">PRINT</a>
<div id="details" class="clearfix" style="font-weight: bold;">
    <table border="0" cellspacing="0" cellpadding="0" width="40%">
        <tr>
            <td>Kapada Yth.</td>
        </tr>
        <tr>
            <td>{{ $consolidator->NAMACONSOLIDATOR }}</td>
        </tr>
        <tr>
            <td>{{ $consolidator->ALAMAT }}<br />{{ $consolidator->NPWP }}</td>
        </tr>
    </table>
    
    @if(count($invoices) > 0)
    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align: center;">INVOICE</td>
        </tr>
        <tr>
            <td style="text-align: center;">NO. {{$no_kwitansi}}</td>
        </tr>
        <tr>
            <td style="text-align: center;">REKAPITULASI NOTA TAGIHAN</td>
        </tr>
        <tr>
            <td style="text-align: center;">Biaya Penumpukan Barang LCL Gudang PT WIRA MITRA PRIMA</td>
        </tr>
        <tr>
            <td style="text-align: center;">Tanggal : {{ date('d F Y', strtotime($start_date)).' - '.date('d F Y', strtotime($end_date)) }}</td>
        </tr>
    </table>
    
    <table border="0" cellspacing="0" cellpadding="0" style="border-top: 1px solid;border-right: 1px solid">
        
        <tr>
            <th style="text-align: center;width: 20px;border: 1px solid;">NO</th>
            <th style="text-align: center;border: 1px solid;">CONSIGNEE</th>
            <th style="text-align: center;width: 80px;border: 1px solid;">TGL<br />RELEASE</th>
            <th style="text-align: center;width: 80px;border: 1px solid;">NO<br />NOTA</th>
            <th style="text-align: center;border: 1px solid;" colspan="2">JUMLAH</th>
        </tr>
        
        <?php $i=1; ?>
        @foreach($invoices as $invoice)
            <tr>
                <td style="text-align: center;border-right: 1px solid;border-left: 1px solid;">{{ $i }}</td>
                <td style="border-right: 1px solid;">{{ $invoice->CONSIGNEE }}</td>
                <td style="text-align: center;border-right: 1px solid;">{{ date('d M Y', strtotime($invoice->tgl_keluar)) }}</td>
                <td style="text-align: center;border-right: 1px solid;">{{ $invoice->no_invoice }}</td>
                <td style="width: 30px;">Rp.</td>
                <td style="text-align: right;width: 150px;">{{ number_format($invoice->sub_total) }}</td>
            </tr>
        <?php $i++;?>
        @endforeach
        
        <tr>
            <td colspan="3" style="border-top:1px solid;"></td>
            <td style="text-align: center;border-top:1px solid;border-right: 1px solid;border-left: 1px solid;">Sub Total</td>
            <td style="border-top:1px solid;">Rp.</td>
            <td style="text-align: right;border-top:1px solid;">{{ number_format($sub_total) }}</td>
        </tr>
        @if($ppn > 0)
        <tr>
            <td colspan="3"></td>
            <td style="text-align: center;border-right: 1px solid;border-left: 1px solid;">PPn 10%</td>
            <td>Rp.</td>
            <td style="text-align: right;border-right: 1px solid;">{{ number_format($ppn) }}</td>
        </tr>
        @endif
        <tr>
            <td colspan="3"></td>
            <td style="text-align: center;border-right: 1px solid;border-left: 1px solid;">Materai</td>
            <td>Rp.</td>
            <td style="text-align: right;">{{ number_format($materai) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td style="text-align: center;border: 1px solid;"><b>TOTAL</b></td>
            <td style="border-bottom: 1px solid;border-top: 1px solid;"><b>Rp.</b></td>
            <td style="text-align: right;border-bottom: 1px solid;border-top: 1px solid;"><b>{{ number_format($total) }}</b></td>
        </tr>
        
    </table>
    <p><b><i># {{ $terbilang }} #</i></b></p>
    <table border="0" cellspacing="0" cellpadding="0">
        <tr><td height="50" style="font-size: 50px;line-height: 0;">&nbsp;</td></tr>
        <tr>
            <td>&nbsp;</td>
            <td class="text-center">Jakarta, {{ date('d F Y', strtotime($tgl_cetak)) }}</td>
        </tr>
        <tr>
            <td width='60%'>
                <span><b>Pembayaran Melalui Rekening Bank an. PT. WIRA MITRA PRIMA</b></span><br />
                <span><b>BCA Cabang Enggano Tj. Priok : 007.303.7601</b></span><br />
                <span><b>Bank Cab. Kelapa Gading Boulevard : 125 000 486 1415</b></span>
            </td>
            <td class="text-center">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="text-center">Yeni Maria<br /></td>
        </tr>
    </table>
    
    @endif
</div>

@stop