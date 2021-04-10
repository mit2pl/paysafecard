<?php
/*

    This class create payment

*/
namespace Mit2\Paysafecard\Payments;

use Illuminate\Support\Facades\Log;

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
        if($this->currency($createvalue['currency']) === true)
        {
            if($this->amount($createvalue['amount']) === true) {
                echo "udalo sies";
            }else {
                abort(404);
            }
        }else {
            abort(404);
        }
       // return $cos[currency];
    }

    //get curl information
    private function getcurl() {
        $startcurl = curl_init();
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
}