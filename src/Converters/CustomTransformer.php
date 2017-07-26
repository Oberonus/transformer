<?php namespace Oberonus\Transformer\Converters;

use Oberonus\Transformer\Transformer;

class CustomTransformer
{
    public function handle($key, $value, $transformerClass)
    {
        /** @var Transformer $transformer */
        $transformer = new $transformerClass;

        if (is_callable([$value, 'toArray'])) {
            $value = $value->toArray();
        }

        $transformed = $transformer->transform($value);

        return [$key, $transformed];
    }
}