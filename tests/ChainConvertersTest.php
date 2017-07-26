<?php namespace Oberonus\Transformer\Tests;

use Oberonus\Transformer\Transformer;

class ChainConvertersTest extends TestCase
{
    /** @test */
    function it_can_chain_two_converters_for_one_attribute()
    {
        $result = $this->transformer
            ->setFields(['foo'])
            ->setConverters([
                'foo' => [
                    [Transformer::RENAME, 'bar'],
                    [function ($key, $value, $mul) {
                        return [$key, $value * $mul];
                    }, 2],
                ]
            ])->transform(['foo' => 2]);

        $this->assertEquals(['bar' => 4], $result);
    }

    /** @test */
    function it_can_chain_any_number_of_converters_and_attributes()
    {
        $converters = [
            'foo' => [
                [Transformer::RENAME, 'bar'],
                [function ($key, $value, $mul) {
                    return [$key, $value * $mul];
                }, 2],
            ],
            'ant' => [
                [function ($key, $value) {
                    return [$key, $value === 15 ? 'fifty' : 'dunno'];
                }, 15],
                [Transformer::RENAME, 'tna'],
            ]
        ];

        $result = $this->transformer
            ->setFields(['foo', 'baz', 'ant'])
            ->setConverters($converters)
            ->transform(['foo' => 2, 'baz' => 5, 'ant' => 15]);

        $this->assertEquals(['bar' => 4, 'baz' => 5, 'tna' => 'fifty'], $result);
    }
}