@extends('layout')

@section('content')
<section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <b>{{ $manifest->NAMACONSOLIDATOR }}</b>
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
                    <b>{{$consignee->NAMAPERUSAHAAN}}</b><br />
                    {{$consignee->ALAMAT}}<br />
                    @if(!empty($manifest->ID_CONSIGNEE))
                        {{$manifest->ID_CONSIGNEE}}
                    @else
                        {{$consignee->NPWP}}
                    @endif
                </p>
            </div>
            <div class="col-sm-7">
                <p style="text-align: center;">No. Invoice : {{ $invoice->no_invoice }}</p>
            </div>
        </div>
      <div class="col-sm-4 invoice-col">
          <table>
              <tr>
                  <td><b>Vessel</b></td>
              </tr>
              <tr>
                  <td><b>Tgl. Stripping</b></td>
              </tr>
              <tr>
                  <td><b>Container No.</b></td>
              </tr>
              <tr>
                  <td><b>Quantity</b></td>
              </tr>
              
          </table>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <table>
              <tr>
                  <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $manifest->VESSEL }}</td>
              </tr>
            <tr>
                  <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ date('d F Y', strtotime($manifest->tglstripping)) }}</td>
              </tr>
              <tr>
                  <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $manifest->NOCONTAINER }} / {{ $manifest->SIZE }}</td>
              </tr>
              <tr>
                  <td>:&nbsp;&nbsp;&nbsp;&nbsp;{{ $manifest->QUANTITY }} {{ $manifest->KODE_KEMAS }}</td>
              </tr>
          </table>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
          <table>
                <tr>
                  <td><b>DO</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->NOMBL }}</td>
              </tr>
                <tr>
                  <td><b>B/L</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->NOHBL }}</td>
              </tr>
              <tr>
                  <td><b>Date Out</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <!--<td>{{ date('d F Y', strtotime($manifest->tglrelease)) }}</td>-->
                  <td>{{ date('d F Y', strtotime($invoice->tgl_keluar)) }}</td>
              </tr>
                <tr>
                  <td><b>Date In</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ date('d F Y', strtotime($manifest->tglmasuk)) }}</td>
              </tr>
              <tr>
                  <td><b>Jumlah Hari</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $invoice->hari }} Hari</td>
              </tr>
                <tr>
                  <td><b>GW</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->WEIGHT }} KGS</td>
              </tr>
                <tr>
                  <td><b>Meas</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->MEAS }} CBM/KGS</td>
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
            <th>&nbsp;</th>
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
              <td align="right">{{ number_format($invoice->sub_total) }}</td>
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
              <td align="right"><b>{{ number_format($invoice->sub_total + $invoice->ppn + $invoice->materai) }}</b></td>
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
        window.open("{{ route('invoice-print',$invoice->id) }}","preview invoice ","width=600,height=600,menubar=no,status=no,scrollbars=yes");
    });
</script>

@endsection