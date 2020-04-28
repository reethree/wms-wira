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
                    <b>{{$consignee->NAMAPERUSAHAAN}}</b><br />
                    {{$consignee->ALAMAT}}<br />
                    {{$consignee->NPWP}}
                  </td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td align="right">
                      No. Invoice : {{ $invoice->no_invoice }}<br />
                      Date {{ ($invoice->tgl_cetak != "") ? date("d F Y", strtotime($invoice->tgl_cetak)) : date("d F Y") }}
                  </td>
              </tr>
              <tr>
                  <td colspan="5">&nbsp;</td>
              </tr>
              <tr>
                  <td><b>Vessel</b></td>
                  <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $manifest->VESSEL }}</td>
                  <td><b>DO</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->NOMBL }}</td>
              </tr>
              <tr>
                  <td><b>Tgl. Stripping</b></td>
                  <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ date('d F Y', strtotime($manifest->tglstripping)) }}</td>
                  <td><b>B/L</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->NOHBL }}</td>
              </tr>
              <tr>
                  <td><b>Container No.</b></td>
                  <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $manifest->NOCONTAINER }} / {{ $manifest->SIZE }}</td>
                  <td><b>Date Out</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <!--<td>{{ date('d F Y', strtotime($manifest->tglrelease)) }}</td>-->
                  <td>{{ date('d F Y', strtotime($invoice->tgl_keluar)) }}</td>
              </tr>
              <tr>
                  <td><b>Quantity</b></td>
                  <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $manifest->QUANTITY }} {{ $manifest->KODE_KEMAS }}</td>
                  <td><b>Date In</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ date('d F Y', strtotime($manifest->tglmasuk)) }}</td>
              </tr>
              <tr>
                  <td><b>GW</b></td>
                  <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $manifest->WEIGHT }} KGS</td>
                  <td><b>Jumlah Hari</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $invoice->hari }} Hari</td>
              </tr>
              <tr>
                  <td>Meas</td>
                  <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $manifest->MEAS }} CBM/KGS</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
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
                  <td colspan="7" style="border-bottom: 1px solid #000;"></td>
              </tr>
            <tr>
              <th>No.</th>
              <th>Kegiatan</th>
              <th>M3</th>     
              <th class="text-center" colspan="2">Tarif</th>
              <th class="text-center" colspan="2">Jumlah Biaya</th>
            </tr>
            </thead>
            <tbody>
            @if($invoice->storage > 0)
          <tr>
            <td>&nbsp;</td>
            <td>Biaya Penumpukan</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>1</td>           
            <td>Storage</td>
            <td>{{ number_format($invoice->cbm * 1000, 0, '.', ',') }} Cbm x {{ $invoice->hari }} hari</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($tarif->storage) }}</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($invoice->storage) }}</td>
          </tr>
          @else
          <tr>
              <td>1</td>
              <td>Biaya Storage</td>
              <td colspan="5">&nbsp;</td>
          </tr>
          <tr>
              <td>&nbsp;</td>
              <td>Masa I (1-3 Hari)</td>
              <td>{{ number_format($invoice->cbm, 2, '.', ',') }} Cbm x {{ $invoice->hari_masa1 }} hari</td>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($tarif->storage_masa1) }}</td>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($invoice->storage_masa1) }}</td>
          </tr>
          <tr>
              <td>&nbsp;</td>
              <td>Masa II (4-6 Hari)</td>
              <td>{{ number_format($invoice->cbm, 2, '.', ',') }} Cbm x {{ $invoice->hari_masa2 }} hari</td>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($tarif->storage_masa2) }}</td>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($invoice->storage_masa2) }}</td>
          </tr>
          <tr>
              <td>&nbsp;</td>
              <td>Masa III (7 Hari - dst)</td>
              <td>{{ number_format($invoice->cbm, 2, '.', ',') }} Cbm x {{ $invoice->hari_masa3 }} hari</td>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($tarif->storage_masa3) }}</td>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($invoice->storage_masa3) }}</td>
          </tr>
          @endif
          <tr>
            <td>2</td>
            <td>Warehouse Charge</td>
            <td>{{ number_format($invoice->cbm, 3, '.', ',') }} Cbm</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($tarif->warehouse_charge) }}</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($invoice->warehouse_charge) }}</td>
          </tr>
          <tr>
            <td>3</td>
            <td>Behandle</td>
            <td>{{ number_format($invoice->behandle, 3, '.', ',') }} Cbm</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($tarif->behandle) }}</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($invoice->harga_behandle) }}</td>
          </tr>
          <tr>
            <td>4</td>
            <td>Surveyor</td>
            <td>{{ number_format($invoice->cbm, 3, '.', ',') }} Cbm</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($tarif->surveyor) }}</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($invoice->surveyor) }}</td>
          </tr>
          <tr>
            <td>5</td>
            <td>DG Cargo Surcharge</td>
            <td>{{ number_format($invoice->cbm, 3, '.', ',') }} Cbm</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($tarif->dg_surcharge) }}</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($invoice->dg_surcharge) }}</td>
          </tr>
          <tr>
            <td>6</td>
            <td>OW Cargo Surcharge > 2.5 Ton</td>
            <td>-</td>
            <td align="right">{{ ($tarif->surcharge_price > 100) ? 'Rp.' : '%' }}</td>
            <td align="right">{{ number_format($tarif->surcharge_price) }}</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($invoice->weight_surcharge) }}</td>
          </tr>
          <tr>
            <td>7</td>
            <td>Admin</td>
            <td>-</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($tarif->adm) }}</td>
            <td align="right">Rp.</td>
            <td align="right">{{ number_format($invoice->adm) }}</td>
          </tr>
            <tr>
                <td colspan="7" style="border-bottom: 1px solid #000;"></td>
            </tr>
            <tr>
                <td colspan="7">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2">Terbilang:</td>
                <td>&nbsp;</td>
                
              <td align="right">Subtotal</td>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($invoice->sub_total) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2" class="dotted"><b>{{ strtoupper($terbilang) }}</b></td>
                <td>&nbsp;</td>
              <td align="right">PPN 10%</td>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($invoice->ppn) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              <td align="right">Materai</td>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($invoice->materai) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right"><b>TOTAL</b></td>
              <td align="right"><b>Rp.</b></td>
              <td align="right"><b>{{ number_format($invoice->sub_total + $invoice->ppn + $invoice->materai) }}</b></td>
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
                <span><b>Pembayaran Melalui Rekening Bank BCA</b></span><br />
                <span><b>No. Rek : 0073037601 cabang Tanjung Priok</b></span><br />
                <span><b>an. PT. Wira Mitra Prima</b></span>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="text-center">Alil Ibnuharja</td>
        </tr>
    </table>
    </div>

@stop