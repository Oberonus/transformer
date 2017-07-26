<?php namespace Oberonus\Transformer\Converters;

use Oberonus\Transformer\Transformer;

class CustomCollectionTransformer
{
    public function handle($key, $value, $transformerClass)
    {
        /** @var Transformer $transformer */
        $transformer = new $transformerClass;

        if (is_callable([$value, 'toArray'])) {
            $value = $value->toArray();
        }

        $transformed = $transformer->transformCollection($value);

        return [$key, $transformed];
    }
}