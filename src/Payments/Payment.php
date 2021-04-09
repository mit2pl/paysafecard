<?php
/*

    This class create payment

*/
namespace Mit2\Paysafecard\Payments;

use Illuminate\Support\Facades\Log;

Class Payment {

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

    public function create(array $createvalue) {
        // print_r($createvalue['amount']);
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
    
    //check if currency is ok
    public function currency($valuecurrency) {
        //accept currency CHF, CZK, DKK, EUR, RON, GBP, NOK, PLN, SEK, and USD
        if($valuecurrency == 'CHF' OR $valuecurrency == 'CZK' OR $valuecurrency == 'DKK' OR $valuecurrency == 'EUR' OR $valuecurrency == 'RON' OR $valuecurrency == 'GBP' OR $valuecurrency == 'NOK' OR $valuecurrency == 'PLN' OR $valuecurrency == 'SEK' OR $valuecurrency == 'USD') {
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