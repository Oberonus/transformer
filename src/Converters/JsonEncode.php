<?php namespace Oberonus\Transformer\Converters;

class JsonEncode
{
    public function handle($key, $value)
    {
        return [$key, json_encode($value)];
    }
}