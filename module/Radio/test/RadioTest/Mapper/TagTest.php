<?php


namespace RadioTest\Mapper;


use Radio\Controller\Atom;

use Radio\Mapper\ArrayFieldSetter;
use Radio\Mapper\ChildCollection;
use Radio\Mapper\Field;
use Radio\Mapper\ChildObject;
use Radio\Mapper\ObjectFieldSetter;
use Radio\Mapper\ObjectMapper;
use Radio\Mapper\Tag;
use RadioTest\Controller\TestBase;

class TagTest extends TestBase
{

    protected function setUp()
    {
        $this->initTest("Atom", new Atom());
    }


    public function testExtractTags()
    {
        //given
        $content = "asd\nlajos #nagyon #okos";
        $t = new Tag("content", $this->em);

        //when
        $tags = $t->extractTags($content);

        //then
        $this->assertEquals(2, sizeof($tags));
        $this->assertEquals("nagyon", $tags[0]->getName());
    }

    public function testAtInEmail()
    {
        //given
        $content = "asd\nlajos #okos asd@asd.hu asd";
        $t = new Tag("content", $this->em);

        //when
        $tags = $t->extractTags($content);

        //then
        $this->assertEquals(1, sizeof($tags));
        $this->assertEquals("okos", $tags[0]->getName());
    }

    public function testNoTagl()
    {
        //given
        $content = "asd\nlajos #okos asd@asd.hu ##nemtag";
        $t = new Tag("content", $this->em);

        //when
        $tags = $t->extractTags($content);

        //then
        $this->assertEquals(1, sizeof($tags));
        $this->assertEquals("okos", $tags[0]->getName());
    }


    public function testExtractSpecialChars()
    {
        //given
        $content = "asd\nlajos #útka-parÓ #{Kovácsolt Vas} #okos";
        $t = new Tag("content", $this->em);

        //when
        $tags = $t->extractTags($content);

        //then
        $this->assertEquals(3, sizeof($tags));
        $this->assertEquals("útka-parÓ", $tags[0]->getName());
        $this->assertEquals("Kovácsolt Vas", $tags[2]->getName());
    }

    public function testMap()
    {

        //given
        $a = [];
        $a['content'] = "#contentx asd asd\nvalami #txag we";
        $t = new Tag("content", $this->em);
        $to = [];

        //when
        $t->map($a, $to, new ArrayFieldSetter());

        //then
        $from = $a;
        $this->assertArrayHasKey("tags", $from);
        $this->assertEquals(2, sizeof($from['tags']));
        $this->assertEquals("contentx", $from['tags'][0]['name']);
        $this->assertEquals("txag", $from['tags'][1]['name']);
        $this->assertEquals(1, $from['tags'][1]['id']);


    }

    public function testMixed()
    {

        //given
        $a = [];
        $a['content'] = "#contentx asd asd\nvalami @person we #{Ham burger}";
        $t = new Tag("content", $this->em);
        $to = [];

        //when
        $t->map($a, $to, new ArrayFieldSetter());

        //then
        $from = $a;
        $this->assertArrayHasKey("tags", $from);
        $this->assertEquals(3, sizeof($from['tags']));
        $this->assertEquals("contentx", $from['tags'][0]['name']);
        $this->assertEquals("person", $from['tags'][2]['name']);
        $this->assertEquals("Ham burger", $from['tags'][1]['name']);
        $this->assertEquals(1, $from['tags'][2]['type']);



    }
}