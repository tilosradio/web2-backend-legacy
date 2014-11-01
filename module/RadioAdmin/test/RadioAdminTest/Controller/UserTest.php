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
use Zend\Json\Json;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;

class UserTest extends TestBase
{

    protected function setUp()
    {
        $this->initTest("RadioAdmin\Controller\User", new User());
        $this->baseData();
        $this->routeMatch->setParam("permission", "guest");

    }

    public function testExamplePassword()
    {
        $u = new \Radio\Entity\User();
        $u->setPassword("asdasdasdasd");
        echo "\n";
        echo "UPDATE user SET salt='" . $u->getSalt() . "',password='". $u->getPassword() . "' WHERE id =";
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
        $this->user = $this->createUser(1, "admin", "admin");

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

    public function testUpdateUser()
    {
        $this->user = $this->createUser(1, "admin", "admin");
        //given
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'update');
        $this->request->setMethod("put");
        $this->request->getHeaders()->addHeaderLine("content-type: application/json");
        $this->request->setContent(Json::encode(
            ['email' => 'asd@asd.hu', 'role' => ['id' => 2]]));

        //when
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $res = $result->getVariables();
        var_dump($res);
        $this->assertTrue($res['success']);
        $user = $this->em->find("\Radio\Entity\User", 1);
        $this->assertEquals("asd@asd.hu", $user->getEmail());
        $this->assertEquals(2, $user->getRole()->getId());


    }

}

?>