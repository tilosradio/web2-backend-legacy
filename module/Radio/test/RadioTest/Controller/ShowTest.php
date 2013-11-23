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

class ShowTest extends \PHPUnit_Framework_TestCase {

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp() {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new \Radio\Controller\Show();
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'M3u'));
        $this->event = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
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