<?php

namespace App\Library;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Response;

class Accurate {
    protected $session;
    protected $token;

    public function __construct() {
        $this->token = 'Basic '. base64_encode(config('accurate.api_key').':'.config('accurate.api_secret_key'));
    }

    public function oauth() {
        $parameter = http_build_query([
            'client_id'=>config('accurate.api_key'),
            'response_type'=>'code',
            'redirect_uri'=>route('accurate-get-session'),
            'scope'=>'sales_invoice_view sales_invoice_save customer_view item_view'
        ]);
        $url = config('accurate.host').'/oauth/authorize?'.$parameter;
        return $url;
    }

    public function getToken($code) {
        $url = config('accurate.host').'/oauth/token';

        try {
            $client = new Client();
            $result = $client->request('POST', $url, [
                'body'=>http_build_query([
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => route('accurate-get-session')
                ]),
                'headers'=>[
                    'Authorization'=> $this->token,
                    'Content-Type'=> 'application/x-www-form-urlencoded'
                ],
            ]);

            return [
                'is_error'=>false,
                'result'=>json_decode($result->getBody()->getContents(), true)
            ];
        } catch (GuzzleException $th) {
            return [
                'is_error'=>true,
                'message'=>json_decode($th->getResponse()->getBody()->getContents())
            ];
        }
    }

    public function getSession($sign, $access_token) {
        $url = config('accurate.host').'/api/open-db.do';
        try {
            $client = new Client();
            $result = $client->request('POST', $url, [
                'body'=>http_build_query([
                    'id' => 315240,
//                    '_ts' => gmdate('Y-m-d\TH:i:s\Z'),
//                    'sign' => $sign
                ]),
                'headers'=>[
                    'Authorization'=> 'Bearer '.$access_token,
                    'Content-Type'=> 'application/x-www-form-urlencoded'
                ],
            ]);
            return [
                'is_error'=>false,
                'result'=>json_decode($result->getBody()->getContents(), true)
            ];
        } catch (GuzzleException $th) {
            return [
                'is_error'=>true,
                'message'=>json_decode($th->getResponse()->getBody()->getContents())
            ];
        }
    }

    public function saveInvoice($host, $body, $access_token, $session) {
        $url = $host.'/accurate/api/sales-invoice/save.do';
        try {
            $client = new Client();
            $result = $client->request('POST', $url, [
                'body'=>http_build_query($body),
                'headers'=>[
                    'X-Session-ID' => $session,
                    'Authorization'=> 'Bearer '.$access_token,
                    'Content-Type'=> 'application/x-www-form-urlencoded'
                ],
            ]);
            return [
                'is_error'=>false,
                'result'=>json_decode($result->getBody()->getContents(), true)
            ];
        } catch (GuzzleException $th) {
            return [
                'is_error'=>true,
                'result'=>json_decode($th->getResponse()->getBody()->getContents())
            ];
        }
    }

    public function __getSign($parameters = []) {
        $parameters['_ts'] = gmdate('Y-m-d\TH:i:s\Z');
        // Urutkan berdasarkan nama
        ksort($parameters);
        // Trim bagian nilai
        $parameter = array_map('trim', $parameters);
        // Deretkan menjadi satu baris
        $data = '';
        foreach ( $parameter as $nama => $nilai ) {
            // Abaikan nilai kosong
            if ($nilai == '') {
                continue;
            }
            if ($data != '') {
                $data .= '&';
            }
            // URL Encode pada nama dan nilai
            $data .= rawurlencode($nama) . '=' . rawurlencode($nilai);
        }
        // Lakukan HMACSHA256
        $signatureSecretKey = config('accurate.api_signature_secret');
        $hash = hash_hmac('sha256', $data, $signatureSecretKey, true );
        $signature = base64_encode($hash);
        // Hasil Signature
        return $signature;
    }
}