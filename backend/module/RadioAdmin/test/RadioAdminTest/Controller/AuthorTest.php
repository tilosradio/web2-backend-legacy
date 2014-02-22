<?php

namespace RadioAdminTest\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Radio\Controller\Atom;
use RadioAdmin\Controller\Author;
use RadioAdmin\Controller\User;
use RadioTest\Bootstrap;
use RadioTest\Fixitures\BaseData;
use Zend\Http\Request;
use Zend\Json\Json;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;

class AuthorTest extends TestBase
{

    protected function setUp()
    {
        $this->initTest("RadioAdmin\Controller\Author", new Author());
        $this->baseData();
        $this->routeMatch->setParam("permission", "guest");

    }



    public function testUpdateAuthor()
    {
        $this->user = $this->createUser(1, "admin", "admin");
        //given
        $this->routeMatch->setParam('id', '300');
        $this->routeMatch->setParam('action', 'update');
        $this->request->setMethod("put");

        $this->request->getHeaders()->addHeaderLine("content-type: application/json");
        $this->request->setContent(Json::encode(
            ['name' => 'xxx', 'introduction' => 'blabla', 'email' => 'qwe','email'=>"asd@asd.hu"]));

        //when
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $res = $result->getVariables();
        var_dump($res);
        $this->assertTrue($res['success']);
        $user = $this->em->find("\Radio\Entity\Author", 300);
        $this->assertEquals("xxx", $user->getName());
        $this->assertEquals("blabla", $user->getIntroduction());
        $this->assertEquals("asd@asd.hu", $user->getEmail());


    }

}

?>