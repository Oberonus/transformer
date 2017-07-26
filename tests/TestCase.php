<?php namespace Oberonus\Transformer\Tests;

use Oberonus\Transformer\Transformer;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Transformer
     */
    protected $transformer;

    function setUp()
    {
        parent::setUp();
        $this->transformer = new Transformer;
    }
}