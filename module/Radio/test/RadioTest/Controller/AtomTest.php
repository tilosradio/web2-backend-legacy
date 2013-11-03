<?php

namespace RadioTest\Controller;

use RadioTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class AtomTest extends \PHPUnit_Framework_TestCase {

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp() {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new \Radio\Controller\Atom();
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'Atom'));
        $this->event = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    public function testAtomFeed() {
        //when        
        $this->routeMatch->setParam('id', '557');
        $this->routeMatch->setParam('action', 'showFeed');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        
        echo($response->getContent());
    }

}

?>