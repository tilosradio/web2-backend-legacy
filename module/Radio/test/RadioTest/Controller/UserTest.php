<?php

namespace RadioTest\Controller;

use Radio\Controller\Atom;
use RadioTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class UserTest extends TestBase {

    protected function setUp() {
        $this->initTest("User", new \Radio\Controller\User());
    }

    public function testPasswordReset() {
        //when        
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'passwordReset    ');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        var_dump($response);
        //TODO make assertions
        //echo($response->getContent());
    }

}

?>