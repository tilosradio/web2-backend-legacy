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

class EpisodeTest extends \PHPUnit_Framework_TestCase {

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp() {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new \Radio\Controller\Episode();
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'Episode'));
        $this->event = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
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