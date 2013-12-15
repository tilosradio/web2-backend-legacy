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

class ShowTest extends TestBase {


    protected function setUp() {
        $this->initTest("Show", new \Radio\Controller\Show());
    }

    public function testShowGet() {
        //when        
        $this->routeMatch->setParam('id', '531');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $show = $result->getVariables();
        //var_dump($show);
        $this->assertEquals($show['name'], "Cratesoul Radio Show / A barázdán is csomót!");
        $this->assertEquals(sizeof($show['urls']), 2);

    }
    
      public function testShowGetWithAlias() {
        //when        
        $this->routeMatch->setParam('id', 'paholy');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $show = $result->getVariables();
        
        //var_dump($show);
        $this->assertEquals($show['id'], 485);
        
    }

}

?>