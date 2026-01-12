<?php

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\Bitmask;

// BackedEnum with explicit power-of-two values
enum OrderFlag: int
{
    case Gift = 1 << 0; // 1
    case PromoCode = 1 << 1; // 2
    case FreeShipping = 1 << 2; // 4
    case ExpressShipping = 1 << 3; // 8
}

class Order
{
    public ?string $promoCode = null;

    public ?string $giftMessage = null;

    public Bitmask $flags;

    public function __construct()
    {
        $this->flags = Bitmask::tiny();
    }

    public function promo(string $code): self
    {
        $this->promoCode = $code;
        $this->flags = $this->flags->set(OrderFlag::PromoCode);

        return $this;
    }

    public function gift(string $message): self
    {
        $this->giftMessage = $message;
        $this->flags = $this->flags->set(OrderFlag::Gift);

        return $this;
    }

    public function freeShipping(): self
    {
        $this->flags = $this->flags->set(OrderFlag::FreeShipping);

        return $this;
    }

    public function expressShipping(): self
    {
        $this->flags = $this->flags->set(OrderFlag::ExpressShipping);

        return $this;
    }
}

$order = (new Order)
    ->promo('XMAS2024')
    ->gift('Merry Christmas!');

dump([
    'is_gift' => $order->flags->has(OrderFlag::Gift), // true
    'has_promo_code' => $order->flags->has(OrderFlag::PromoCode), // true
    'free_shipping' => $order->flags->has(OrderFlag::FreeShipping), // false
    'express_shipping' => $order->flags->has(OrderFlag::ExpressShipping), // false
]);
