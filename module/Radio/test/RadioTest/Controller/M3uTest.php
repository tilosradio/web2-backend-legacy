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

class IndexControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new \Radio\Controller\M3u();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'M3u'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }
    
    public function testDownloadActionCanBeAccessed()
    {
        //given
        $this->routeMatch->setParam('action', 'download');
        $this->routeMatch->setParam('year', '2013');
        $this->routeMatch->setParam('month', '10');
        $this->routeMatch->setParam('day', '18');
        $this->routeMatch->setParam('from', '1800');
        $this->routeMatch->setParam('to', '1900');
        //when
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();        
        
        //then        
        $this->assertEquals(200, $response->getStatusCode());
        
        
        $body = preg_split("/\n/",$response->getContent());
        $this->assertEquals(8, sizeof($body));
        $this->assertContains("20131018-1830",$body[4]);               
    }
}
?>