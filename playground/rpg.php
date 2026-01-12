<?php

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\Bitmask;

enum AttackType: int
{
    case Melee = 1;
    case Fire = 2;
    case Ice = 4;
    case Poison = 8;
}

class Weapon
{
    public function __construct(public Bitmask $attackTypes) {}
}

$fireSword = new Weapon(
    Bitmask::tiny()
        ->set(AttackType::Melee)
        ->set(AttackType::Fire)
);

dump([
    'allows_fire' => $fireSword->attackTypes->has(AttackType::Fire), // true
    'allows_ice' => $fireSword->attackTypes->has(AttackType::Ice), // false
]);
