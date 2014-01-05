<?php


namespace RadioTest\Mapper;



use Radio\Mapper\ArrayFieldSetter;
use Radio\Mapper\ObjectFieldSetter;


class ArrayFieldSetterTest extends \PHPUnit_Framework_TestCase {

    public function testArraySetting() {

        $a = [];
        $s = new ArrayFieldSetter();
        $v = "qqq";
        $s->set($a,'test',$v);

        $this->assertEquals('qqq',$a['test']);

    }

    public function testObjectSetting() {

        $a = new Obj1();
        $s = new ObjectFieldSetter();
        $v = "qqq";
        $s->set($a,'prop1',$v);

        $this->assertEquals('qqq',$a->prop1);

    }


}