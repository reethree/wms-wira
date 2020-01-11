@extends('print-with-header')

@section('title')
    {{ 'Delivery Surat Jalan' }}
@stop

@section('content')
<style>
    td {
        vertical-align: top;
    }
    @media print {
        body {
            background: #FFF;
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
        <div id="title" style="font-weight: bold;">SURAT JALAN 
            <!--<br /><span style="font-size: 14px;">No. {{$manifest->NOJOBORDER}}</span>-->
        </div>
        <p></p>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0;">
            <tr>
                <td width='60%'>
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;margin-bottom: 0;">

                        <tr>
                            <td>Kepada Yth.</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $consignee->NAMAPERUSAHAAN }}</td>
                        </tr>
                        <tr>
                            <td>No. Surat Jalan</td>
                            <td class="padding-10 text-center">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Ex. Kapal</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->VESSEL }}</td>
                        </tr>
                        <tr>
                            <td>Voy</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->VOY }}</td>
                        </tr>
                        <tr>
                            <td>No. D.O</td>
                            <td class="padding-10 text-center">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Truck No.</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->NOPOL_RELEASE }}</td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;margin-bottom: 0;">
                        <tr>
                            <td>No. SPPB/BC23</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->NO_SPPB.'/KPU.'.date('m',strtotime($manifest->TGL_SPPB)).'/'.date('Y',strtotime($manifest->TGL_SPPB)) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <br />
        <table border="1" cellspacing="0" cellpadding="0">
            <tr>
                <td width='50%'>
                    <h4>DESCRIPTION : </h4>
                    <p>{{ str_limit($manifest->DESCOFGOODS, 150) }}</p>
                    <br />
                </td>
                <td width='50%'>
                    <h4>MARKS : </h4>
                    <p>{{ $manifest->MARKING }}</p>
                    <br />
                </td>
            </tr>
            <tr>
                <td width='50%'>
                    <h4>NOTES : </h4>
                    <br />
                </td>
                <td width='50%'>
                    <h4>RACK / LOCATION :</h4>
                    <p>{{ $manifest->location_name }}</p>
                    <br />
                </td>
            </tr>
        </table>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width='33%' style="text-align: center;vertical-align: bottom;">Penerima<br />Nama Terang</td>
                <td width='33%' style="text-align: center;vertical-align: bottom;"">Sopir Truck<br />Nama Terang</td>
                <td width='33%' style="text-align: center;">Tanjung Priok, {{date('d F Y')}}<br />PT. Wira Mitra Prima<br />Petugas</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">(.............................)</td>
                <td style="text-align: center;">(.............................)</td>
                <td style="text-align: center;">(.............................)</td>
            </tr>
        </table>
    </div>
        
@stop