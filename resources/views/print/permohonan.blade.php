@extends('print')

@section('title')
    {{ 'LCL Permohonan Pindah Lokasi' }}
@stop

@section('content')
<style>
    body {font-size: 12px;}
    table, table tr, table tr td{
        font-size: 12px;
    }
    table {
        margin-bottom: 10px;
    }
    @media print {
        body {
            background: #FFF;
            color: #000;
        }
        @page {
            size: auto;   /* auto is the initial value */
            font-weight: bold;
        }
        .print-btn {
            display: none;
        }
    }
</style>
<a href="#" class="print-btn" type="button" onclick="window.print();">PRINT</a>


    <div id="details" class="clearfix">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>Jakarta, {{ date('d F Y') }}</td>
                <td style="text-align: right;">Nomor : {{$no_surat}}</td>
            </tr>
            <tr><td colspan="2"></td></tr>
            <tr><td colspan="2"></td></tr>
            <tr>
                <td colspan="2">Kepada Yth.</td>
            </tr>
            <tr>
                <td>{{$header->attn}}</td>
            </tr>
            <tr>
                <td>{{$header->nama}}</td>
            </tr>
            <tr>
                <td>{{$header->alamat}}</td>
            </tr>
        </table>
    </div>
    <p>Perihal : Permohonan Pindah Lokasi Penimbunan LCL</p>
    <br />
    
    <p>Dengan Hormat,</p>
    <p>Menunjuk surat permohonan Pindah Lokasi Penimbunan LCL dari {{ $container->NAMACONSOLIDATOR }} tanggal {{ date('d F Y', strtotime($data['tgl_surat'])) }}, dengan ini kami mohon diberikan izin agar container tersebut dapat dipindahlokasikan ke gudang PT. Wira Mitra Prima.<br />Dengan data-data sebagai berikut :</p>
    
    <table class="table">
        <tbody>
            <tr>
                <td>NO. Container</td>
                <td> : </td>
                <td>{{ $container['NOCONTAINER'] }} / {{ $container->SIZE }}'</td>
            </tr>
            <tr>
                <td>Ex. Kapal</td>
                <td> : </td>
                <td>{{ $container['VESSEL'] }} VOY. {{ $container['VOY'] }}</td>
            </tr>
            <tr>
                <td>ETA</td>
                <td> : </td>
                <td>{{ date("d F Y", strtotime($container['ETA'])) }}</td>
            </tr>
            <tr>
                <td>NO. MB/L</td>
                <td> : </td>
                <td>{{ $container['NOMBL'] }}</td>
            </tr>
            <tr>
                <td>NO./TGL BC11</td>
                <td> : </td>
                <td>{{ $data['no_bc'].' / '.date("d-m-Y", strtotime($data['tgl_bc'])) }}</td>
            </tr>
            <tr>
                <td>Jumlah Pos</td>
                <td> : </td>
                <td>{{ $container->jumlah_bl }} POS</td>
            </tr>
            <tr>
                <td>Jumlah Kemasan/GW</td>
                <td> : </td>
                <td>{{ $container['MEAS'].' PKGs' }} / {{ $container['WEIGHT'].' KGs' }}</td>
            </tr>
            <tr>
                <td>S O R</td>
                <td> : </td>
                <td>{{ $data['sor'] }} %</td>
            </tr>
        </tbody>
    </table>
    
    <p>Demikian permohonan ini kami buat, atas perhatian dan kerjasamanya diucapkan terimakasih.</p>
    <br />
    <div style="margin-bottom: 100px;">
        Hormat Kami,
    </div>
    <div>
        <span style="border-bottom: 1px solid;">Muhammad Akbar</span><br />
        Spv. CY & CFS TPS
    </div>
        
@stop