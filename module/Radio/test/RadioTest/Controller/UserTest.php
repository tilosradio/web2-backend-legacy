<?php

namespace RadioTest\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Radio\Controller\Atom;
use Radio\Controller\User;
use RadioTest\Bootstrap;
use RadioTest\Fixitures\BaseData;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class UserTest extends TestBase {

    protected function setUp() {
        $this->initTest("User", new User());
        $loader = new Loader();
        $loader->addFixture(new BaseData());
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());

    }

    public function testPasswordReset() {
        //when        
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'passwordReset');


        $result = $this->controller->dispatch($this->request);

        //then
        $response = $this->controller->getResponse();
        $model = $this->event->getResult()->getVariables();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains("generated", $model['message']);
        $this->assertTrue($model['status']);

        $this->em->createQueryBuilder();

        $q = $this->em->createQueryBuilder()->select("p")->from('\Radio\Entity\ChangePasswordToken', "p")->where('p.user = :user')->getQuery();
        $q->setParameter("user", $this->em->find("\Radio\Entity\User", 1));
        $result = $q->getArrayResult();
        $this->assertEquals(1, count($result));
        $old_token = $result[0]['token'];

        //regeneration
        $result = $this->controller->dispatch($this->request);

        $response = $this->controller->getResponse();
        $model = $this->event->getResult()->getVariables();

        $q = $this->em->createQueryBuilder()->select("p")->from('\Radio\Entity\ChangePasswordToken', "p")->where('p.user = :user')->getQuery();
        $q->setParameter("user", $this->em->find("\Radio\Entity\User", 1));
        $result = $q->getArrayResult();
        $this->assertEquals(1, count($result));
        $new_token = $result[0]['token'];

        $this->assertNotEquals($old_token, $new_token);


    }

    public function testPasswordResetNoUser() {
        //when
        $this->routeMatch->setParam('id', '12');
        $this->routeMatch->setParam('action', 'passwordReset');


        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $model = $this->event->getResult()->getVariables();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains("does not exist", $model['error']);
    }

}

?>