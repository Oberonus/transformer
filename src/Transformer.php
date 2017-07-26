<?php namespace Oberonus\Transformer;

class Transformer
{
    //prebuilt converters
    const DECODE_JSON = Converters\JsonDecode::class;
    const ENCODE_JSON = Converters\JsonEncode::class;
    const RENAME = Converters\KeyRename::class;
    const TRANSFORM = Converters\CustomTransformer::class;
    const TRANSFORM_COLLECTION = Converters\CustomCollectionTransformer::class;

    /**
     * @var ConvertersManager
     */
    protected $convertersManager = null;

    /**
     * An array of field names to process and transform
     * @var array
     */
    protected $fields = [];

    /**
     * Fields converting rules
     * @var array
     */
    protected $converters = [];

    /**
     * @param array $fields
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @param array $converters
     * @return $this
     */
    public function setConverters(array $converters)
    {
        $this->converters = $converters;

        return $this;
    }

    /**
     * Will iterate through collection or array and transform all items inside
     * Items should be an array, or should be able to return an item array via toArray() method
     * Returns a new array of transformed objects
     *
     * @param $items
     * @return array
     */
    public function transformCollection($items)
    {
        $this->createConvertersManager();
        if (is_callable([$items, 'toArray'])) {
            $items = $items->toArray();
        }

        return array_map([$this, 'transform'], $items);
    }

    /**
     * Transforms one item.
     *
     * Item should be an array, or should be able to return an array of fields via toArray() method
     *
     * @param $item
     * @return mixed
     */
    public function transform($item)
    {
        $this->createConvertersManager();
        $this->beforeTransform($item);

        if (is_callable([$item, 'toArray'])) {
            $item = $item->toArray();
        }

        $results = [];
        foreach ($this->fields as $field) {
            $this->processField($item, $field, $results);
        }

        return $results;
    }

    /**
     * Makes transformation of one field in provided item
     *
     * @param array $item Array of fields (keys) and values
     * @param string $field Field name to transform
     * @param array $results Resulting array with transformed fields
     */
    protected function processField(array $item, $field, array &$results)
    {
        if (!array_key_exists($field, $item))
            return;

        $keyValue = $this->hasConverters($field)
            ? $this->performConversion($field, $item[$field])
            : [$field, $item[$field]];

        $results[$keyValue[0]] = $keyValue[1];
    }

    /**
     * Transform field and value with through provided converters
     *
     * @param string $field
     * @param mixed $value
     * @return array
     */
    protected function performConversion($field, $value)
    {
        return $this->convertersManager->perform($field, $value, $this->converters[$field]);
    }

    /**
     * Checks if converters exist for provided field
     *
     * @param $field
     * @return bool
     */
    protected function hasConverters($field)
    {
        return isset($this->converters[$field]);
    }

    /**
     * Calls before transformation of an item
     * can be used to perform any modifications before transformation started
     *
     * @param $item
     */
    protected function beforeTransform(&$item)
    {
        return;
    }

    /**
     * Creates converting orchestrator service
     */
    protected function createConvertersManager()
    {
        if ($this->convertersManager instanceof ConvertersManager) return;
        $this->convertersManager = new ConvertersManager($this);
    }
}