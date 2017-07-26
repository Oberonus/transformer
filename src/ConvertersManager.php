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
        $keyValue = [$key, $value];
        $converters = $this->normalizeFormat($converters);

        foreach ($converters as $rules) {
            $keyValue = $this->convert($keyValue[0], $keyValue[1], $rules);
        }

        return $keyValue;
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
     * @param string $key
     * @param mixed $value
     * @param $rules
     * @return array
     * @throws \Exception
     */
    protected function convert($key, $value, $rules)
    {
        $converterType = array_shift($rules);

        if (is_string($converterType) && class_exists($converterType)) {
            return (new $converterType)->handle($key, $value, ...$rules);
        }

        if (is_callable([$this->transformer, $converterType])) {
            return $this->transformer->$converterType($key, $value, ...$rules);
        }

        if (is_callable($converterType)) {
            return call_user_func_array($converterType, array_merge([$key, $value], $rules));
        }

        throw new \Exception('Unknown type of converter provided');
    }
}