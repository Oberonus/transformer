<?php namespace Oberonus\Transformer\Tests;

class ClosureConverterTest extends TestCase
{
    /** @test */
    function it_can_transform_using_custom_method()
    {
        $result = $this->transformer->setFields(['foo', 'bar'])
            ->setConverters([
                'foo' => [$this, 'customFunc']
            ])
            ->transform(['foo' => 1, 'bar' => 5]);
        $this->assertEquals(['foo' => 2, 'bar' => 5], $result);
    }

    /** @test */
    function it_can_transform_through_simple_closure()
    {
        $result = $this->transformer->setFields(['foo', 'bar'])
            ->setConverters([
                'foo' => function ($key, $value) {
                    return [$key, $value + 2];
                }
            ])
            ->transform(['foo' => 1, 'bar' => 5]);
        $this->assertEquals(['foo' => 3, 'bar' => 5], $result);
    }

    /** @test */
    function it_can_pass_parameters_to_custom_converter()
    {
        $result = $this->transformer->setFields(['foo', 'bar'])
            ->setConverters([
                'foo' => [function ($key, $value, $add) {
                    return [$key, $value + $add];
                }, 2]
            ])
            ->transform(['foo' => 1, 'bar' => 5]);
        $this->assertEquals(['foo' => 3, 'bar' => 5], $result);
    }

    public function customFunc($key, $value)
    {
        return [$key, $value + 1];
    }
}