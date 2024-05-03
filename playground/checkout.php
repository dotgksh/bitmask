<?php

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\TinyBitmask;

enum OrderFlag: int
{
    case Gift = 1 << 0; // 1
    case PromoCode = 1 << 1; // 2
    case FreeShipping = 1 << 2; // 4
    case ExpressShipping = 1 << 3; // 8
}

class OrderFlags extends TinyBitmask
{
    public function enable(OrderFlag $flag): OrderFlags
    {
        return $this->set($flag->value);
    }

    public function disable(OrderFlag $flag): OrderFlags
    {
        return $this->unset($flag->value);
    }

    public function enabled(OrderFlag $flag): bool
    {
        return $this->has($flag->value);
    }
}

class Order
{
    public ?string $promoCode = null;

    public ?string $giftMessage = null;

    public OrderFlags $flags;

    public function __construct()
    {
        $this->flags = OrderFlags::make();
    }

    public function promo(string $code): self
    {
        $this->promoCode = $code;
        $this->flags->enable(OrderFlag::PromoCode);

        return $this;
    }

    public function gift(string $message): self
    {
        $this->giftMessage = $message;
        $this->flags->enable(OrderFlag::Gift);

        return $this;
    }

    public function freeShipping(): self
    {
        $this->flags->enable(OrderFlag::FreeShipping);

        return $this;
    }

    public function expressShipping(): self
    {
        $this->flags->enable(OrderFlag::ExpressShipping);

        return $this;
    }
}

$order = (new Order())
    ->promo('XMAS2024')
    ->gift('Merry Christmas!');

dump([
    'is_gift' => $order->flags->enabled(OrderFlag::Gift), // true
    'has_promo_code' => $order->flags->enabled(OrderFlag::PromoCode), // true
    'free_shipping' => $order->flags->enabled(OrderFlag::FreeShipping), // false
    'express_shipping' => $order->flags->enabled(OrderFlag::ExpressShipping), // false
]);
