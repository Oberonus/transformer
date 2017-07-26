<?php namespace Oberonus\Transformer;

class ConvertersManager
{
    /**
     * @var Transformer
     */
    private $transformer;

    public function __construct(Transformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param $key
     * @param $value
     * @param $converters
     * @return array
     */
    public function perform($key, $value, $converters)
    {
        return array_reduce(
            $this->normalizeFormat($converters), [$this, 'convert'], [$key, $value]
        );
    }

    /**
     * @param $converters
     * @return array
     */
    protected function normalizeFormat($converters)
    {
        if (!is_array($converters) || is_callable($converters)) return [[$converters]];
        if (!is_array($converters[0]) || is_callable($converters[0])) return [$converters];

        return $converters;
    }

    /**
     * @param $keyValue
     * @param $rules
     * @return array
     * @throws \Exception
     * @internal param string $key
     * @internal param mixed $value
     */
    protected function convert($keyValue, $rules)
    {
        $converterType = array_shift($rules);

        /*
         * If provided converter is a class
         * make new instance and call 'handle' function
         */
        if (is_string($converterType) && class_exists($converterType)) {
            return (new $converterType)->handle($keyValue[0], $keyValue[1], ...$rules);
        }

        /*
         * If provided converter is a method name of the provided transformer object
         * call it directly
         */
        if (is_string($converterType) && is_callable([$this->transformer, $converterType])) {
            return $this->transformer->$converterType($keyValue[0], $keyValue[1], ...$rules);
        }

        /*
         * If provided converter is simple callable,
         * call it directly
         */
        if (is_callable($converterType)) {
            return call_user_func_array($converterType, array_merge($keyValue, $rules));
        }

        throw new \Exception('Unknown type of converter provided');
    }
}