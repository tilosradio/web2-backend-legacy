<?php


namespace RadioTest\Controller;


use Radio\Formatter\RestrictedHtmlTransformer;
use Radio\Formatter\TagFormatter;

class TagFormatterTest extends \PHPUnit_Framework_TestCase
{

    public function testSimple()
    {
        $t = new TagFormatter();
        $this->assertEquals("asd <span>tag</span> qwe",$t->format("asd #tag qwe"));
    }
}