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

class TextTest extends \PHPUnit_Framework_TestCase {

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp() {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new \Radio\Controller\Text();
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

    public function testListOfType() {
        //when        
        $this->routeMatch->setParam('action', 'listOfType');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $show = $result->getVariables();
        var_dump($show);
        $this->assertEquals($show['alias'], "haza-es-haladas");
    }

}

?>