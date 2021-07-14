# paysafecard
To install package:

```php
composer require mit2/paysafecard
```

To create payment

```php

$cos = new Payment;
       $cos->create([
            'amount' => '10.1',
            'currency' => 'PLN',
            'success_url' => 'http://plemiona.com/tak',
            'failed_url' => 'http://plemiona.com/nie',
            'notification_url' => 'http://plemiona.com/czekamy',
            // 'kyclevel' => '',
            'customer_mail' => Auth::user()->email,
           ]);
return redirect($cos->request['request_url']);

```
