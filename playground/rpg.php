<?php

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\TinyBitmask;

enum AttackType: int
{
    case None = 0;
    case Melee = 1;
    case Fire = 2;
    case Ice = 4;
    case Poison = 8;
}

class AttackTypes extends TinyBitmask
{
    public function add(AttackType $type): AttackTypes
    {
        return $this->set($type->value);
    }

    public function remove(AttackType $type): AttackTypes
    {
        return $this->unset($type->value);
    }

    public function allows(AttackType $type): bool
    {
        return $this->has($type->value);
    }
}

class Weapon
{
    public function __construct(public AttackTypes $attackTypes)
    {
    }
}

$fireSword = new Weapon(
    AttackTypes::make()
        ->add(AttackType::Melee)
        ->add(AttackType::Fire)
);

dump([
    'allows_fire' => $fireSword->attackTypes->allows(AttackType::Fire), // true
    'allows_ice' => $fireSword->attackTypes->allows(AttackType::Ice), // false
]);
