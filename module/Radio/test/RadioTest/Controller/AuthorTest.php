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

class AuthorTest extends \PHPUnit_Framework_TestCase {

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp() {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new \Radio\Controller\Author();
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'Author'));
        $this->event = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    public function testGet() {
        //when        
        $this->routeMatch->setParam('id', '936');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $author = $result->getVariables();
        
        $this->assertContains("aktivista",$author['introduction']);
        $this->assertNotEmpty($author['photo']);
        
    }
    
     public function testGetWithAlias() {
        //when        
        $this->routeMatch->setParam('id', 'sztyepp');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $author = $result->getVariables();
        
        //var_dump($author);
        $this->assertContains("uzginuver",$author['introduction']);
        $this->assertNotEmpty($author['photo']);
        
    }

}

?>