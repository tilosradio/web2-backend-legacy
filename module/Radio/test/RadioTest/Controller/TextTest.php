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

class TextTest extends TestBase {

    protected function setUp() {
        $this->initTest("Text", new \Radio\Controller\Text());
    }

    public function testListOfType() {
        //when        
        $this->routeMatch->setParam('action', 'listOfType');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

//        $shows = $result->getVariables();
        //var_dump($show);
        //$this->assertEquals($shows[0]['alias'], "haza-es-haladas");
    }

}

?>