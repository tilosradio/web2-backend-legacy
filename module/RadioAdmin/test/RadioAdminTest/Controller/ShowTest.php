<?php

namespace RadioAdminTest\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Radio\Controller\Atom;
use RadioAdmin\Controller\Show;
use RadioAdmin\Controller\User;
use RadioTest\Bootstrap;
use RadioTest\Fixitures\BaseData;
use Zend\Http\Request;
use Zend\Json\Json;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;

class ShowTest extends TestBase
{

    protected function setUp()
    {
        $this->initTest("RadioAdmin\Controller\Show", new Show());
        $this->baseData();
        $this->routeMatch->setParam("permission", "guest");

    }



    public function testCreateShow()
    {
        $this->user = $this->createUser(1, "admin", "admin");
        //given

        $this->routeMatch->setParam('action', 'create');
        $this->request->setMethod("post");

        $this->request->getHeaders()->addHeaderLine("content-type: application/json");
        $this->request->setContent(Json::encode(
            ['name' => 'xxx', 'description' => 'blabla', 'alias' => 'qwe','email'=>"asd@asd.hu"]));

        //when
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $res = $result->getVariables();
        //var_dump($res);

        $this->assertTrue($res['success']);
        $id = $res['data']['id'];
        $show = $this->em->find("\Radio\Entity\Show", $id);
        $this->assertNotEmpty($show);
        $this->assertEquals("xxx", $show->getName());
        $this->assertEquals("blabla", $show->getDescription());
        $this->assertEquals("qwe", $show->getAlias());



    }

    

}

?>