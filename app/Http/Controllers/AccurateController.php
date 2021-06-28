<?php


namespace App\Http\Controllers;

use App\Models\InvoiceNct;
use Illuminate\Http\Request;
use App\Library\Accurate;
use DB;

class AccurateController extends Controller
{
    protected $accurate;
    public function __construct() {
        $this->accurate = new Accurate();
    }

    public function oauth(Request $request) {
        $data = [];
        $response = $this->accurate->oauth();
        return response()->json([
            'url'=>$response
        ]);
    }

    public function getSession(Request $request) {
        $response = $this->accurate->getToken($request->code);

        if($response['is_error']) {
            return back()->with('error', $response['message']);
        }
        $data = [
            'token_type' => $response['result']['token_type'],
            'refresh_token' => $response['result']['refresh_token'],
            'expires_in' => $response['result']['expires_in'],
            'scope' => $response['result']['scope'],
            'uid' => 1
        ];

        if(DB::table('accurate_token')->updateOrInsert(
            ['access_token' => $response['result']['access_token']],
            $data
        )) {
            echo "<div style='padding:20px 20px 0px 20px;font-family:verdana;'><h3 style='margin:0px;padding:0px;'>Verifikasi ACCURATE.</h3><p>Mohon tunggu proses sedang berjalan...<p></div>";
            $token = DB::table('accurate_token')->orderBy('id','desc')->first();
            if (!$token) {
                echo "<div style='padding:20px;'><h1>Tidak ditemukan token</h1></div>";
                echo "<script>window.close();</script>";
                return;
            }
            $access_token = $token->access_token;
            $sign = $this->accurate->__getSign(array('id' => 315240));

            $response = $this->accurate->getSession('', $access_token);

            if($response['is_error']) {
//                return back()->with('error', $response['message']['d']);
                echo "<div style='padding:20px;'><h1>".$response['message']['d']."</h1></div>";
                return;
            }

            $data = [
                'host' => $response['result']['host'],
                'session' => $response['result']['session']
            ];
            if(DB::table('accurate_session')->updateOrInsert(
                ['session' => $response['result']['session']],
                $data
            )) {
                echo "<script>setTimeout(function(){window.close();}, 1000);</script>";
                return;
            }
        }
    }

    public function saveInvoice(Request $request) {

        $id = $request->id;
        $kode = $request->code;
        $type = $request->type;
        if($type=='fcl'){
            $item_code = 100033;
        }elseif($type=='lcl'){
            $item_code = 100027;
        }else{
            // Materai
//            $item_code = 100034;
        }

        $invoice = InvoiceNct::find($id);

        if (!$invoice) {
            return response()->json([
                'success'=>false,
                'message'=>'Invoice tidak ditemukan.'
            ]);
        }

        $session = DB::table('accurate_session')->orderBy('created_at','desc')->first();
        $token = DB::table('accurate_token')->orderBy('id','desc')->first();
        $access_token = $token->access_token;
        $session_id = $session->session;

//        data[n].customerNo
//        data[n].detailItem[n].itemNo
//        data[n].detailItem[n].unitPrice
//        data[n].orderDownPaymentNumber
//        data[n].reverseInvoice
//        data[n].taxDate
//        data[n].taxNumber
//        data[n].transDate

        $body = [
            'branchName'=> 'Kantor Pusat',
            'customerNo' => $kode,
            'description' => 'TEST FCL From WMS',
            'detailItem[0].itemNo' => $item_code,
            'detailItem[0].unitPrice' => $invoice->total_non_ppn,
            'reverseInvoice' => 0,
            'session' => $session->session,
            'taxDate' => date('d/m/Y', strtotime($invoice->gateout_tps)),
            'taxNumber' => $invoice->no_invoice,
            'transDate' =>  date('d/m/Y', strtotime($invoice->gateout_tps)),
        ];
//        $sign = $this->accurate->__getSign($body);
//        $body['_ts'] = gmdate('Y-m-d\TH:i:s\Z');
//        $body['sign'] = $sign;
        $response = $this->accurate->saveInvoice($session->host, $body, $access_token, $session_id);

        if (!$response['is_error']) {
            if ($response['result']['s']){
                $invoice->uploaded_accurate = 1;
                if($invoice->save()){
                    return response()->json([
                        'success'=>true,
                        'response'=>$response,
                        'message'=>$response['result']['d']
                    ]);
                }
            }else{
                return response()->json([
                    'success'=>false,
                    'response'=>$response,
                    'message'=>$response['result']['d']
                ]);
            }
        }

        return response()->json([
            'success'=>false,
            'response'=>$response,
            'message'=>$response['result']['d']
        ]);
    }
}