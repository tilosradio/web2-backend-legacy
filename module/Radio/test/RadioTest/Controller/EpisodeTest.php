<?php

namespace RadioTest\Controller;

use Radio\Controller\Episode;
use RadioTest\Bootstrap;
use Zend\Form\Element\Radio;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class EpisodeTest extends TestBase {

    protected function setUp() {
        $this->initTest("Radio\Controller\Episode", new Episode());
        $this->baseData();
    }

    public function testEpisodeList() {
        //when        
        $this->request->setUri("/api/episode");

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then
        $episodes = $result->getVariables();
        //var_dump($episodes);
        $this->assertTrue(count($episodes) > 0);
    }

}

?>