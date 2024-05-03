<?php

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\TinyBitmask;

enum PaymentMethod: int
{
    case Cash = 1;
    case Credit = 2;
    case Debit = 4;
    case Check = 8;
}

class PaymentMethods extends TinyBitmask
{
    public function accept(PaymentMethod $method): PaymentMethods
    {
        return $this->set($method->value);
    }

    public function reject(PaymentMethod $method): PaymentMethods
    {
        return $this->unset($method->value);
    }

    public function accepts(PaymentMethod $method): bool
    {
        return $this->has($method->value);
    }
}

class Invoice
{
    public function __construct(public PaymentMethods $paymentMethods)
    {
    }
}

$invoice = new Invoice(
    PaymentMethods::make()
        ->accept(PaymentMethod::Cash)
        ->accept(PaymentMethod::Debit)
);

dump([
    'accepts_debit' => $invoice->paymentMethods->accepts(PaymentMethod::Debit), // true
    'accepts_credit' => $invoice->paymentMethods->accepts(PaymentMethod::Credit), // false
]);
