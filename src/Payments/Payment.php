<?php
/*

    This class create payment

*/
namespace Mit2\Paysafecard\Payments;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request as Getrequest;

Class Payment {

    private $currencychoes = array('CHF', 'CZK', 'DKK', 'EUR', 'RON', 'GBP', 'NOK', 'PLN', 'SEK', 'USD');

    public function getlink() {
        //in this function we get link to website
        if(config('paysafecard.psc_mode') == 'live') {
            return "https://api.paysafecard.com/v1/payments/";
        }else if(Config('paysafecard.psc_mode') == 'test') {
            return "https://apitest.paysafecard.com/v1/payments/";
        }else {
            if(Config('paysafecard.psc_logs') == '1') {
                Log::debug('You chose wrong Psc mode');
            }
            abort(404);
        }
    }

    //create payment
    public function create(array $createvalue) {
        $getrequest = new Getrequest;
        if($this->currency($createvalue['currency']) === true)
        {
            if($this->amount($createvalue['amount']) === true) {

                //change amount from , to .
                $amonts = str_replace(',', '.', $createvalue['amount']);

                //add customer information
                $customersinfo = array(
                    "id" => $createvalue['customer_mail'],
                    "ip" => $getrequest->ip(),
                );

                // check if you choose age and push
                if($createvalue['minimumage'] != "") {
                    array_push($customersinfo, "min_age", $createvalue['minimumage']);
                }

                // check if keylevel is exist
                if($createvalue['kyclevel'] != "") {
                    if($this->checkkyc($createvalue['kyclevel'] === true))
                    {
                        array_push($customersinfo, "kyc_level", $createvalue['kyclevel']);
                    }
                }
                // put all information
                $fullinformation = array(
                    "currency" => $createvalue['currency'],
                    "amount" => $amonts,
                    "customer" => $customersinfo,
                    "redirect" => array(
                        "success_url" => $createvalue['success_url'],
                        "failure_url" => $createvalue['failed_url'],
                    ),
                    "type" => "PAYSAFECARD",
                    "notification_url" => $createvalue['notification_url'],
                    "shop_id" => Config("paysafecard.psc_shopid"),

                );
                //check if submerchant id exist
                if($createvalue['submerchantid'] != "") {
                    array_push($fullinformation, "submerchant_id", $createvalue['submerchantid']);
                }

                if($createvalue['correlationid'] != "")
                {
                    $head = ["Correlation-ID: ". $createvalue['correlationid']];
                } else {
                    $head = [];
                }

                $this->getcurl($fullinformation, "POST", $head);
                if($this->checkrequiest() == true) {
                    return $this->response;
                } else {
                    return "jednak nie pyklo";
                }
            }else {
                abort(404);
            }
        }else {
            abort(404);
        }
       // return $cos[currency];
    }

    //get curl information
    private function getcurl($parameterrequire, $method) {
        $startcurl = curl_init();

        $keysend = array(
            "Authorization: Basic " . base64_encode(Config("paysafecard.psc_key")),
            "Content-Type: application/json",
        );
        //add key to curl
        curl_setopt($startcurl, CURLOPT_HTTPHEADER, $keysend);

        if($method == "POST") {
            curl_setopt($startcurl, CURLOPT_URL, $this->getlink());
            curl_setopt($startcurl, CURLOPT_POSTFIELDS, json_encode($parameterrequire));
            curl_setopt($startcurl, CURLOPT_POST, true);
        } elseif($method == "GET") {
            if(!empty($parameterrequire)) {
                curl_setopt($startcurl, CURLOPT_URL, $this->getlink() . $parameterrequire);
                curl_setopt($startcurl, CURLOPT_POST, false);
            } else {
                curl_setopt($startcurl, CURLOPT_URL, $this->getlink());
            }
        } else {
            //text ze jest wybrana zla forma
            if(Config('paysafecard.psc_logs') == '1') {
                Log::debug('Connection mode is wrong');
            }
            abort(404);
        }
        curl_setopt($startcurl, CURLOPT_PORT, 443);
        curl_setopt($startcurl, CURLOPT_TIMEOUT, 60);
        curl_setopt($startcurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($startcurl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($startcurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($startcurl, CURLOPT_HEADER, false);

        if(is_array($parameterrequire)) {
            $parameterrequire['request_url'] = $this->getlink();
        } else {
            $geturl              = $this->getlink() . $parameterrequire;
            $parameterrequire                = array();
            $parameterrequire['request_url'] = $geturl;
        }
        $this->request  = $parameterrequire;
        $this->response = json_decode(curl_exec($startcurl), true);

        $this->curl["info"]        = curl_getinfo($startcurl);
        $this->curl["error_nr"]    = curl_errno($startcurl);
        $this->curl["error_text"]  = curl_error($startcurl);
        $this->curl["http_status"] = curl_getinfo($startcurl, CURLINFO_HTTP_CODE);
        curl_close($startcurl);
        $this->getlink();
    }
    
    //check if currency is ok
    public function currency($valuecurrency) {
        //accept currency CHF, CZK, DKK, EUR, RON, GBP, NOK, PLN, SEK, and USD
        if(in_array($valuecurrency, $this->currencychoes)) {
            return true;
        }else {
            if(Config('paysafecard.psc_logs') == '1') {
                Log::debug('You chose wrong currency');
            }
            return false;
        }
    }

    //check if amount is numeric
    public function amount($valueamount) {
        if(is_numeric($valueamount)) {
            return true;
        }else {
            if(Config('paysafecard.psc_logs') == '1') {
                Log::debug('Amount is not numeric');
            }
            return false;
        }
    }

    //check if kyc level is SIMPLE or FULL
    public function checkkyc($kycvalue) {
        if($kycvalue == "SIMPLE" OR $kycvalue == "FULL") {
            return true;
        }else {
            if(Config('paysafecard.psc_logs') == '1') {
                Log::debug('You choose wrond key level, must be SIMPLE or FULL');
            }
            return false;
        }
    }

    //check if request have error 
    public function checkrequiest() {
        if(($this->curl['error_nr'] == 0) && ($this->curl["http_status"] < 300)) 
        {
            return true;
        } else {
            return false;
        }
    }
}