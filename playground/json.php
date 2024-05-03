<?php

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\SmallBitmask;

class JsonFlags extends SmallBitmask
{
}

class Payload
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(public array $data, public JsonFlags $flags)
    {
    }

    public function serialize(): false|string
    {
        return json_encode($this->data, $this->flags->value());
    }
}

$payload = new Payload(
    ['foo' => 'bar', 'baz' => 'qux'],
    JsonFlags::make()
        ->set(JSON_PRETTY_PRINT)
        ->set(JSON_UNESCAPED_SLASHES)
);

dump($payload->serialize());
// {\n
//   "foo": "bar",\n
//   "baz": "qux"\n
// }
