<?php
/*

    This class create payment

*/
namespace Mit2\Paysafecard\Payments;
Class PscpaymentClass {

    public function getlink() {
        //in this function we get link to website
        if(Config::get('paysafecard.psc_mode') == 'live') {
            return "https://api.paysafecard.com/v1/payments/";
        }else if(Config::get('paysafecard.psc_mode') == 'test') {
            return "https://apitest.paysafecard.com/v1/payments/";
        }else {
            Log::debug('An informational message.');
        }
    }
}