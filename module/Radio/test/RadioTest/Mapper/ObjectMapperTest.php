<?php


namespace RadioTest\Mapper;


use Radio\Mapper\ChildCollection;
use Radio\Mapper\Field;
use Radio\Mapper\ChildObject;
use Radio\Mapper\ObjectFieldSetter;
use Radio\Mapper\ObjectMapper;

class ObjectMapperTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleObjectMapping()
    {

        $a = [];
        $a['prop1'] = 12;
        $a['prop2'] = "asd";

        $to = new Obj1();

        $mapper = new ObjectMapper(new ObjectFieldSetter());
        $mapper->addMapper(new Field("prop1"));
        $mapper->addMapper(new Field("prop2"));


        $result = $mapper->map($a, $to);


        $this->assertEquals(12, $to->prop1);
        $this->assertEquals("asd", $to->prop2);


    }

    public function testChildObjectMapping()
    {

        $a = [];
        $a['prop1'] = 12;
        $a['prop2'] = "asd";

        $b = [];
        $b['prop1'] = 25;
        $a['child'] = $b;

        $to = new Obj1();

        $mapper = new ObjectMapper(new ObjectFieldSetter());
        $mapper->addMapper(new Field("prop1"));
        $mapper->addMapper(new Field("prop2"));

        $child = new ChildObject("child", "\RadioTest\Mapper\ChildObj1");
        $mapper->addMapper($child);
        $child->addMapper(new Field("prop1"));

        $result = $mapper->map($a, $to);


        $this->assertEquals(12, $to->prop1);
        $this->assertEquals("asd", $to->prop2);
        $this->assertNotNull($to->child);
        $this->assertEquals(25, $to->child->prop1);


    }

    public function testChildObjectCollection()
    {

        $a = [];
        $a['prop1'] = 12;
        $a['prop2'] = "asd";

        $b1 = [];
        $b1['prop1'] = 25;

        $b2 = [];
        $b2['prop1'] = 26;


        $a['child'] = [$b1, $b2];

        $to = new Obj1();

        $mapper = new ObjectMapper(new ObjectFieldSetter());
        $mapper->addMapper(new Field("prop1"));
        $mapper->addMapper(new Field("prop2"));

        $child = new ChildCollection("child", "\RadioTest\Mapper\ChildObj1");
        $mapper->addMapper($child);
        $child->addMapper(new Field("prop1"));

        $result = $mapper->map($a, $to);

        $this->assertEquals(12, $to->prop1);
        $this->assertEquals("asd", $to->prop2);
        $this->assertNotNull($to->child);

        $this->assertEquals(2, count($to->child));
        $this->assertEquals(25, $to->child[0]->prop1);
        $this->assertEquals(26, $to->child[1]->prop1);

    }
}