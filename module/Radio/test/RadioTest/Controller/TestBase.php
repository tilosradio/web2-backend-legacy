<?php


namespace RadioTest\Controller;


use HttpRequest;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;
use RadioTest\Bootstrap;

class TestBase extends \PHPUnit_Framework_TestCase {

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function initTest($controllerName, $controller) {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = $controller;;
        $this->request = new \Zend\Http\PhpEnvironment\Request();
        $this->routeMatch = new RouteMatch(array('controller' => $controllerName));
        $this->event = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);


    }

} 