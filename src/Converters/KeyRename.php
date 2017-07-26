<?php namespace Oberonus\Transformer\Converters;

class KeyRename
{
    public function handle($key, $value, $newKey)
    {
        return [$newKey, $value];
    }
}