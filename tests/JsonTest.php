<?php namespace Oberonus\Transformer\Tests;


use Oberonus\Transformer\Transformer;

class JsonTest extends TestCase
{
    /** @test */
    function it_can_decode_json()
    {
        $data = [
            'json' => '{"foo":"bar"}',
        ];

        $converters = [
            'json' => Transformer::DECODE_JSON,
        ];

        $result = $this->transformer->setFields(['json'])->setConverters($converters)->transform($data);

        $this->assertEquals([
            'json' => ['foo' => 'bar'],
        ], $result);
    }

    /** @test */
    function it_can_encode_json()
    {
        $data = [
            'json' => ['foo' => 'bar', 'baz' => 5],
        ];

        $converters = [
            'json' => Transformer::ENCODE_JSON,
        ];

        $result = $this->transformer->setFields(['json'])->setConverters($converters)->transform($data);
        
        $this->assertEquals([
            'json' => '{"foo":"bar","baz":5}',
        ], $result);
    }
}