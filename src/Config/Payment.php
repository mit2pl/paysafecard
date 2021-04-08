<?php

/*
This package is working on Laravel 8
Create by: mit2
*/

return [
    /*
        You can choose two mode:
        test = to testing your code
        live = working live
    */
    'psc_mode'    => env('PSC_MODE', 'live'),
    /*
        Your Paysafecard key
    */
    'psc_key'   => env("PSC_KEY", ''),
    /*
        You can enable error logs:
        1 - Enable
        0 - Disable
    */
    'psc_logs'     => env('PSC_LOGGED', '1'),
    /*
        Debuging your code
        0 - disable, ou don't see errors (on live mode please turn of to users don't see errors)
        1 - enable, you see all errors
    */
    'psc_debug'         => env('PSC_DEBUG', '0'),
];