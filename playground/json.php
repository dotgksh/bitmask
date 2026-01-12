<?php

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\Bitmask;

class Payload
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(public array $data, public Bitmask $flags) {}

    public function serialize(): false|string
    {
        return json_encode($this->data, $this->flags->value());
    }
}

$payload = new Payload(
    ['foo' => 'bar', 'baz' => 'qux'],
    Bitmask::small()
        ->set(JSON_PRETTY_PRINT)
        ->set(JSON_UNESCAPED_SLASHES)
);

dump($payload->serialize());
// {\n
//   "foo": "bar",\n
//   "baz": "qux"\n
// }
