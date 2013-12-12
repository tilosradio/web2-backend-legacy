<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/12/13
 * Time: 8:02 PM
 */

namespace RadioTest\Mapper;


use Radio\Mapper\ChildCollection;
use Radio\Mapper\Field;
use Radio\Mapper\ChildObject;

class Mapper extends \PHPUnit_Framework_TestCase {

    public function testSimpleMapping() {

        $a = [];
        $a['id'] = 12;
        $a['name'] = "asd";
        $a['volatile'] = "qwe";

        $to = [];

        $mapper = new \Radio\Mapper\ObjectMapper();
        $mapper->addMapper(new FieldMapper("id"));
        $mapper->addMapper(new FieldMapper("name"));

        $result = $mapper->map($a, $to);

        $this->assertArrayHasKey("id", $to);
        $this->assertArrayHasKey("name", $to);
        $this->assertArrayNotHasKey("volatile", $to);
        $this->assertEquals(12, $to['id']);

    }

    public function testChildMapping() {

        $a = [];
        $a['id'] = 12;
        $a['name'] = "asd";
        $a['volatile'] = "qwe";

        $b = [];
        $b['id'] = 25;
        $a['b'] = $b;

        $to = [];

        $mapper = new \Radio\Mapper\ObjectMapper();
        $mapper->addMapper(new Field("id"));
        $mapper->addMapper(new Field("name"));

        $child = new ChildObject("b");
        $mapper->addMapper($child);
        $child->addMapper(new Field("id"));

        $result = $mapper->map($a, $to);

        $this->assertArrayHasKey("id", $to);
        $this->assertArrayHasKey("name", $to);
        $this->assertArrayNotHasKey("volatile", $to);
        $this->assertEquals(12, $to['id']);

        $this->assertArrayHasKey("b", $to);
        $this->assertArrayHasKey("id", $to['b']);
        $this->assertEquals(25, $to['b']['id']);

    }

    public function testChildCollection() {

        $a = [];
        $a['id'] = 12;
        $a['name'] = "asd";
        $a['volatile'] = "qwe";

        $b1 = [];
        $b1['id'] = 25;

        $b2 = [];
        $b2['id'] = 25;

        $a['b'] = [$b1, $b2];

        $to = [];

        $mapper = new \Radio\Mapper\ObjectMapper();
        $mapper->addMapper(new Field("id"));
        $mapper->addMapper(new Field("name"));

        $child = new ChildCollection("b");
        $mapper->addMapper($child);
        $child->addMapper(new Field("id"));

        $result = $mapper->map($a, $to);

        $this->assertArrayHasKey("id", $to);
        $this->assertArrayHasKey("name", $to);
        $this->assertArrayNotHasKey("volatile", $to);
        $this->assertEquals(12, $to['id']);

        $this->assertArrayHasKey("b", $to);
        $this->assertArrayHasKey("id", $to['b'][0]);
        $this->assertEquals(25, $to['b'][0]['id']);

    }
}