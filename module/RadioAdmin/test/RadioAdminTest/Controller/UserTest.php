<?php

namespace RadioAdminTest\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Radio\Controller\Atom;
use RadioAdmin\Controller\User;
use RadioTest\Bootstrap;
use RadioTest\Fixitures\BaseData;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;

class UserTest extends TestBase
{

    protected function setUp()
    {
        $this->initTest("Radio\Controller\User", new User());
        $this->baseData();
        $this->routeMatch->setParam("permission","guest");

    }

    public function testExamplePassword()
    {
        $u = new \Radio\Entity\User();
        $u->setPassword("asdasdasdasd");
        echo "\n";
        echo $u->getSalt()."\n";
        echo $u->getPassword()."\n";
    }

    public function testCurrentUser()
    {
        //when
        $this->user = null;
        $this->routeMatch->setParam('action', 'currentUserAction');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $model = $this->event->getResult()->getVariables();
        $this->assertEquals([], $model);
    }

    public function testGetUser()
    {
        //when

        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'get');


        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $model = $this->event->getResult()->getVariables();
        //var_dump($model);
        $this->assertEquals('test', $model['username']);
        $this->assertNotEmpty($model['author']);
    }


    public function testCurrentUserReal()
    {
        //when
        $this->user = $this->createUser(1, "admin", "admin");

        $this->routeMatch->setParam('action', 'currentUserAction');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $model = $this->event->getResult()->getVariables();
        //var_dump($model);
        $this->assertEquals('test', $model['username']);
        $this->assertEquals(300, $model['author']['id']);

    }


}

?>