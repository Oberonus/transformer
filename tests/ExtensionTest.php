<?php namespace Oberonus\Transformer\Tests;

use Oberonus\Transformer\Transformer;

class Multiplication
{
    public function handle($key, $value, $mul)
    {
        return [$key, $value * $mul];
    }
}

class NewTransformer extends Transformer
{
    const MULTIPLICATION = Multiplication::class;
    const SUBSTRING = 'subString';

    protected $fields = ['foo', 'bar', 'kraz', 'kraz2'];
    protected $converters = [
        'foo'   => [[NewTransformer::MULTIPLICATION, 3], [NewTransformer::RENAME, 'oof']],
        'bar'   => [NewTransformer::RENAME, 'baz'],
        'kraz'  => ['subString', 2, 4],
        'kraz2' => [NewTransformer::SUBSTRING, 3, 2],
    ];

    public function subString($key, $value, $start = 0, $length = 1)
    {
        return [$key, substr($value, $start, $length)];
    }
}

class ExtensionTest extends TestCase
{
    /** @test */
    function it_can_be_extended_by_new_converters_by_inheritance()
    {
        $transformer = new NewTransformer;
        $result = $transformer->transform(['foo' => 4, 'bar' => 'someval', 'kraz' => 'mycoolstring', 'kraz2' => '12345678']);

        $this->assertEquals(['oof' => 12, 'baz' => 'someval', 'kraz' => 'cool', 'kraz2' => '45'], $result);
    }
}