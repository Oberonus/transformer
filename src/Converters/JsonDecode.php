<?php namespace Oberonus\Transformer\Converters;

class JsonDecode
{
    public function handle($key, $value, $assoc = true)
    {
        return [$key, json_decode($value, $assoc)];
    }
}