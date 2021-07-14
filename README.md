# paysafecard
To install package:

```php
composer require mit2/paysafecard

php artisan vendor:publish --provider "Mit2\PaysafecardProviders\PaysafecardProvider"

```env
add to env file
```
# choose test or live mode
PSC_MODE = test
# enter psc key
PSC_KEY = psc_testkey
#enable logs
PSC_LOGGED = 1
# unique shop id
PSC_SHOPID = 
```
To create payment

```php
use Mit2\Paysafecard\Payments\Payment;

$payment = new Payment;
       $payment->create([
            'amount' => '10',
            'currency' => 'PLN',
            'success_url' => route('payment.success'),
            'failed_url' => route('payment.failed'),
            'notification_url' => route('payment.notificationurl'),
            'customer_mail' => Auth::user()->email,
           ]);
return redirect($cos->request['request_url']);

```


