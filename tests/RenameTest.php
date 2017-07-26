<?php
namespace Oberonus\Transformer\Tests;

use Oberonus\Transformer\Transformer;

class RenameTest extends TestCase
{
    /** @test */
    function it_can_rename_attribute()
    {
        $result = $this->transformer
            ->setFields(['foo'])
            ->setConverters(['foo' => [Transformer::RENAME, 'bar']])
            ->transform(['foo' => 2]);
        $this->assertEquals(2, $result['bar']);
        $this->assertCount(1, $result);
    }

    /** @test */
    function it_can_rename_many_fields()
    {
        $result = $this->transformer
            ->setFields(['foo', 'bar'])
            ->setConverters([
                'foo' => [Transformer::RENAME, 'oof'],
                'bar' => [Transformer::RENAME, 'rab'],
            ])
            ->transform(['foo' => 2, 'bar' => 3]);
        $this->assertEquals(['oof' => 2, 'rab' => 3], $result);
    }

    /** @test */
    function it_will_not_touch_other_attributes_when_renaming()
    {
        $result = $this->transformer
            ->setFields(['foo', 'bar'])
            ->setConverters(['foo' => [Transformer::RENAME, 'oof']])
            ->transform(['foo' => 2, 'bar' => 3]);
        $this->assertCount(2, $result);
        $this->assertEquals(['oof' => 2, 'bar' => 3], $result);
    }
}