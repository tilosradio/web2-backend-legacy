<?php

namespace RadioAdminTest\Controller;

use RadioTest\Bootstrap;
use Zend\Json\Json;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class SchedulingTest extends TestBase
{


    protected function setUp()
    {
        $this->initTest("Radio\Controller\Scheduling", new \RadioAdmin\Controller\Scheduling());
        $this->baseData();
    }

    public function testSchedulingList()
    {
        //when        
        $this->routeMatch->setParam('show', '1');
        $this->routeMatch->setParam('action', 'getList');


        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $schedulings = $result->getVariables();
        $this->assertTrue(is_array($schedulings));
        $this->assertEquals(2, count($schedulings));
        $this->assertEquals(2, $schedulings[0]['id']);
        $this->assertEquals(1, $schedulings[1]['id']);
        $this->assertTrue(is_numeric($schedulings[0]['validFrom']));


    }

    public function testGetScheduling()
    {
        //when
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'get');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $scheduling = $result->getVariables();
        //var_dump($scheduling);
        $this->assertTrue(is_numeric($scheduling['validFrom']));


    }

    public function testUpdateScheduling()
    {
        $this->user = $this->createUser(1, "admin", "admin");
        //given
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'update');
        $this->request->setMethod("put");
        $this->request->getHeaders()->addHeaderLine("content-type: application/json");
        $this->request->setContent(Json::encode(
            ['weekDay' => 5]));

        //when
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $res = $result->getVariables();
        // var_dump($res);
        $this->assertTrue($res['success']);
        $sched = $this->em->find("\Radio\Entity\Scheduling", 1);
        $this->assertEquals(5, $sched->getWeekDay());


    }

    public function testCreateScheduling()
    {
        $this->user = $this->createUser(1, "admin", "admin");
        //given
        $this->routeMatch->setParam('show', '1');
        $this->routeMatch->setParam('action', 'create');
        $this->request->setMethod("post");
        $this->request->getHeaders()->addHeaderLine("content-type: application/json");
        $this->request->setContent(Json::encode(
            [
                'weekDay' => 5,
                'weekType' => 1,
                'hourFrom' => 1,
                'minFrom' => 1,
                'duration' => 1,
                'weekType' => 1,
                'validFrom' => mktime(10, 0, 0, 1, 1, 2012),
                'validTo' => mktime(10, 0, 0, 1, 1, 2019),
                'base' => mktime(10, 0, 0, 1, 1, 2012)]));

        //when
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $res = $result->getVariables();
        //var_dump($res);
        $this->assertTrue($res['success']);
        $sched = $this->em->find("\Radio\Entity\Scheduling", $res['data']['id']);
        $this->assertEquals(5, $sched->getWeekDay());
        $this->assertEquals(1, $sched->getMinFrom());
        $this->assertNotNull($sched->getShow());


    }

    public function testDeleteScheduling()
    {
        $this->user = $this->createUser(1, "admin", "admin");
        //given
        $this->routeMatch->setParam('show', '1');
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'delete');
        $this->request->setMethod("delete");

        //when
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $res = $result->getVariables();
        //var_dump($res);
        $this->assertTrue($res['success']);
        $sched = $this->em->find("\Radio\Entity\Scheduling", 1);
        $this->assertNull($sched);


    }


}

?>