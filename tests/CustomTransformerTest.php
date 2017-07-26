<?php namespace Oberonus\Transformer\Tests;

use Oberonus\Transformer\Transformer;

class CustomTransformer extends Transformer
{
    protected $fields = ['name', 'mul_by_two'];
    protected $converters = [
        'name'       => [Transformer::RENAME, 'newname'],
        'mul_by_two' => 'multiply',
    ];

    public function multiply($key, $value)
    {
        return [$key, $value * 2];
    }
}

class CustomTransformerTest extends TestCase
{
    /** @test */
    function it_allows_to_run_custom_transformers_inside_transformation_cycle()
    {
        $converters = [
            'array' => [Transformer::TRANSFORM, CustomTransformer::class]
        ];

        $data = [
            'foo'   => 2,
            'array' => [
                'name'       => 4,
                'mul_by_two' => 8,
            ]
        ];

        $result = $this->transformer
            ->setFields(array_keys($data))
            ->setConverters($converters)
            ->transform($data);

        $this->assertEquals([
            'foo'   => 2,
            'array' => [
                'newname'    => 4,
                'mul_by_two' => 16
            ]
        ], $result);
    }

    /** @test */
    function it_can_perform_custom_transform_on_the_array_of_arrays()
    {
        $converters = [
            'array' => [
                [Transformer::TRANSFORM_COLLECTION, CustomTransformer::class],
                [Transformer::RENAME, 'renamed_array']
            ]
        ];

        $data = [
            'foo'   => 2,
            'array' => [
                ['name' => 4, 'mul_by_two' => 8],
                ['name' => 6, 'mul_by_two' => 7],
            ]
        ];

        $result = $this->transformer
            ->setFields(array_keys($data))
            ->setConverters($converters)
            ->transform($data);

        $this->assertEquals([
            'foo'   => 2,
            'renamed_array' => [
                ['newname' => 4, 'mul_by_two' => 16],
                ['newname' => 6, 'mul_by_two' => 14],
            ]
        ], $result);
    }
}