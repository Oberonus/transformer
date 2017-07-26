<?php namespace Oberonus\Transformer\Tests;

class TransformerTest extends TestCase
{
    /** @test */
    function it_throws_out_not_listed_attributes()
    {
        $transformed = $this->transformer
            ->setFields(['foo', 'bar'])
            ->transform(['foo' => 1, 'bar' => 2, 'baz' => 3]);
        $this->assertEquals(['foo' => 1, 'bar' => 2], $transformed);
    }

    /** @test */
    function it_omits_non_existing_fields_in_source_array()
    {
        $transformed = $this->transformer
            ->setFields(['foo', 'bar', 'mar'])
            ->transform(['foo' => 1, 'bar' => 2, 'baz' => 3]);
        $this->assertEquals(['foo' => 1, 'bar' => 2], $transformed);
    }
}