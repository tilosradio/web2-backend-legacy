<?php

namespace RadioTest\Controller;

use RadioTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class SchedulingTest extends TestBase
{


    protected function setUp()
    {
        $this->initTest("Radio\Controller\Scheduling", new \Radio\Controller\Scheduling());
        $this->baseData();
    }

    public function testSchedulingList()
    {
        //when        
        $this->routeMatch->setParam('show', '1');

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

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $scheduling = $result->getVariables();
        //var_dump($scheduling);
        $this->assertTrue(is_numeric($scheduling['validFrom']));


    }


}

?>