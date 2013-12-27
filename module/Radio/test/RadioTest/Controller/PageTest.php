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

class PageTest extends TestBase {

    protected function setUp() {
        $this->initTest("Radio\Controller\Text", new \Radio\Controller\Text());
        $this->baseData();
    }

    public function testPageGet() {
        //when        
        $this->routeMatch->setParam('id', '1');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $page = $result->getVariables();
        //var_dump($page);
        $this->assertContains("Jelentőség", $page['content']);
    }
    
    public function testPageGetWithAlias() {
        //when        
        $this->routeMatch->setParam('id', 'info');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $page = $result->getVariables();
        //var_dump($page);
        $this->assertContains("Jelentőség", $page['content']);
        
    }

}

?>