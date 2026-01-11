<?php

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\Bitmask;

enum PaymentMethod: int
{
    case Cash = 1;
    case Credit = 2;
    case Debit = 4;
    case Check = 8;
}

class Invoice
{
    public function __construct(public Bitmask $paymentMethods) {}
}

// Create invoice accepting Cash and Debit only
$invoice = new Invoice(
    Bitmask::tiny()
        ->set(PaymentMethod::Cash)
        ->set(PaymentMethod::Debit)
);

dump([
    'accepts_debit' => $invoice->paymentMethods->has(PaymentMethod::Debit), // true
    'accepts_credit' => $invoice->paymentMethods->has(PaymentMethod::Credit), // false
]);
