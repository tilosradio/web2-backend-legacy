<?php

namespace RadioTest\Controller;

use Radio\Controller\Episode;
use RadioTest\Bootstrap;
use Zend\Form\Element\Radio;
use Zend\Json\Json;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;
use Zend\Stdlib\Parameters;

class EpisodeTest extends TestBase {

    protected function setUp() {
        $this->initTest("Radio\Controller\Episode", new Episode());
        $this->baseData();
        $this->user = $this->createUser(1, "admin", "admin");
    }

    public function testEpisodeList() {
        $start = mktime(10, 0, 0, 1, 28, 2013);
        $end = mktime(15, 0, 0, 1, 28, 2013);
        //when        
        $this->request->setUri("/api/episode?start=$start&end=$end");
        $this->request->setQuery(new Parameters(['start' => $start, 'end' => $end]));

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then                      e
        $episodes = $result->getVariables();
        //var_dump($episodes);
        $this->assertTrue(count($episodes) > 0);
    }

    public function testEpisodeCreate() {
        $start = mktime(10, 0, 0, 10, 28, 2013);
        $end = mktime(12, 0, 0, 10, 28, 2013);
        //when
        $this->request->setUri("/api/episode");
        $this->request->setMethod("post");
        $this->request->getHeaders()->addHeaderLine("content-type: application/json");
        $this->request->setContent(Json::encode(
            ['plannedFrom' => $start,
                'plannedTo' => $end,
                'radioshow_id' => 1]));

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then
        $episode = $result->getVariables();


        $this->assertTrue($episode['success']);
        $id = $episode['data']['id'];

        $persisted = $this->em->find("\Radio\Entity\Episode", $id);
        $this->assertEquals($id, $persisted->getId());
        $this->assertEquals($start, $persisted->getPlannedFrom()->getTimestamp());
        $this->assertEquals($end, $persisted->getPlannedTo()->getTimestamp());
        $this->assertNull($persisted->getText());


    }

    public function testEpisodeEditWithText() {
        $start = mktime(10, 0, 0, 10, 28, 2013);
        $end = mktime(12, 0, 0, 10, 28, 2013);
        //when
        $this->request->setUri("/api/episode/1");
        $this->request->setMethod("put");
        $this->request->getHeaders()->addHeaderLine("content-type: application/json");
        $this->request->setContent(Json::encode(
            [
                'content' => 'new content',
                'title' => 'ccc'
            ]));
        $this->routeMatch->setParam('id', 1);


        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        //then
        $episode = $result->getVariables();
        //var_dump($episode);

        $this->assertTrue($episode['success']);
        $id = $episode['data']['id'];

        $persisted = $this->em->find("\Radio\Entity\Episode", $id);
        $this->assertEquals($id, $persisted->getId());

        $this->assertNotNull($persisted->getText());
        $this->assertEquals("ccc", $persisted->getText()->getTitle());
        $this->assertEquals("new content", $persisted->getText()->getContent());


    }

    public function testEpisodeCreateWithText() {
        $start = mktime(10, 0, 0, 10, 28, 2013);
        $end = mktime(12, 0, 0, 10, 28, 2013);
        //when
        $this->request->setUri("/api/episode");
        $this->request->setMethod("post");
        $this->request->getHeaders()->addHeaderLine("content-type: application/json");
        $this->request->setContent(Json::encode(
            ['plannedFrom' => $start,
                'plannedTo' => $end,
                'content' => 'content, content',
                'title' => 'title',
                'radioshow_id' => 1]));

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        //then
        $episode = $result->getVariables();
        //var_dump($episode);

        $this->assertTrue($episode['success']);
        $id = $episode['data']['id'];

        $persisted = $this->em->find("\Radio\Entity\Episode", $id);
        $this->assertEquals($id, $persisted->getId());
        $this->assertEquals($start, $persisted->getPlannedFrom()->getTimestamp());
        $this->assertEquals($end, $persisted->getPlannedTo()->getTimestamp());
        $this->assertNotNull($persisted->getText());
        $this->assertEquals("title", $persisted->getText()->getTitle());
        $this->assertEquals("content, content", $persisted->getText()->getContent());


    }

    public function testEpisodeGet() {
        $start = mktime(10, 0, 0, 10, 28, 2013);
        $end = mktime(12, 0, 0, 10, 28, 2013);
        //when
        $this->request->setUri("/api/episode/1");
        $this->request->setMethod("get");
        $this->routeMatch->setParam('id', 1);


        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then
        $episode = $result->getVariables();
        var_dump($episode);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(count($response) > 0);
        $this->assertEquals(1, $episode['id']);


    }

}

?>