<?php

namespace RadioTest\Controller;

use Radio\Controller\Atom;
use RadioTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class AtomTest extends TestBase {

    protected function setUp() {
        $this->initTest("Atom", new Atom());
    }

    public function testAtomFeed() {
        //when        
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'showFeed');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        //TODO make assertions
        //echo($response->getContent());
    }

}

?>