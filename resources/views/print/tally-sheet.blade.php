@extends('print')

@section('title')
    {{ 'Manifest Tally Sheet' }}
@stop

@section('content')
        
<style>
    table, table tr, table tr td{
        font-size: 11px;
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
    <div id="details" class="clearfix">
        <div id="title" style="font-size: 12px;font-weight: bold;">TALLY SHEET STRIPPING</div>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;">
                        <tr>
                            <td>Consolidator</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->NAMACONSOLIDATOR }}</td>
                        </tr>
                        <tr>
                            <td>No. Container</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->NOCONTAINER }}/{{ $container->SIZE }}</td>
                        </tr>
                        <tr>
                            <td>Kapal</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->VESSEL }}</td>
                        </tr>
                        <tr>
                            <td>Voy</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->VOY }}</td>
                        </tr>
                        <tr>
                            <td>Tgl.Tiba</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ date("d/m/Y", strtotime($container->ETA)) }}</td>
                        </tr>
                        
                    </table> 
                </td>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;">
<!--                        <tr>
                            <td>No. Order</td>
                            <td class="padding-10 text-center">:</td>
                            <td></td>
                        </tr>-->
                        <tr>
                            <td>Gudang</td>
                            <td class="padding-10 text-center">:</td>
                            <td>PT. WIRA MITRA PRIMA</td>
                        </tr>
                        <tr>
                            <td>Tangal</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ date('d-m-Y', strtotime($container->STARTSTRIPPING)) }}</td>
                        </tr>
                        <tr>
                            <td>Mulai Stripping</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ date('H:i', strtotime($container->STARTSTRIPPING)) }}</td>
                        </tr>
                        <tr>
                            <td>Selesai Stripping</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ date('H:i', strtotime($container->ENDSTRIPPING)) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>          
            
        </table>
    </div>
    <table border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>NO</th>
                <th>CONSIGNEE</th>
                <th>MERK</th>
                <th>TALLY</th>
                <th>QTY</th>   
                <th>REMARKS</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;?>
            @foreach($manifests as $manifest)
            <tr>
                <td style="width: 20px;height: 70px;" class="text-center">{{ $i }}</td>
                <td style="width: 80px;">{{ $manifest->CONSIGNEE }}</td>
                <td style="width: 150px;">{{ str_limit($manifest->MARKING, 100) }}</td>
                <td style="width: 100px;"></td>
                <td style="width: 50px;" class="text-center">{{ $manifest->QUANTITY }} {{ $manifest->packing }}</td>
                <td></td>
            </tr>
            <?php $i++;?>
            @endforeach         
        </tbody>
    </table>
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td colspan="3" style="text-align: right;">Jakarta, {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="text-center" style="padding-top: 80px;"><b>Mengetahui</b></td>
                <td class="text-center" style="padding-top: 80px;"><b>Surveyor</b></td>
                <td class="text-center" style="padding-top: 80px;"><b>Petugas Tally</b></td>
            </tr>
            <tr>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td class="text-center" style="padding-top: 80px;"><b>(..................................)</b></td>
                <td class="text-center" style="padding-top: 80px;"><b>(..................................)</b></td>
                <td class="text-center" style="padding-top: 80px;"><b>(..................................)</b></td>
            </tr>
        </tbody>
    </table>
    
@stop