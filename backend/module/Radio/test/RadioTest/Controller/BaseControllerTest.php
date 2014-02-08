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

class BaseControllerTest extends TestBase
{


    public static function testDeny()
    {
        return false;
    }
    public static function testAccess()
    {
        return true;
    }

    protected function setUp()
    {
        $this->initTest("RadioTest\Controller\BaseTestableController", new BaseTestableController());
        $this->baseData();
    }

    public function testGuestAccess()
    {
        //when        
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'get');
        $this->routeMatch->setParam("tilosRouter", true);
        $this->routeMatch->setParam("permission", "guest");
        $this->controller->setAccessDenied(false);


        $result = $this->controller->checkRouteAccess($this->event);
        $response = $this->controller->getResponse();
        //then

        $this->assertFalse($this->controller->getAccessDenied());


    }

    public function testAdminAccessDenied()
    {

        //when
        $this->user = null;
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'get');
        $this->routeMatch->setParam("tilosRouter", true);
        $this->routeMatch->setParam("permission", "admin");
        $this->controller->setAccessDenied(false);


        $result = $this->controller->checkRouteAccess($this->event);
        $response = $this->controller->getResponse();
        //then

        $this->assertTrue($this->controller->getAccessDenied());


    }

    public function testAdminAccessAllow()
    {
        $this->user = $this->createUser(1, "admin", "admin");
        //when
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'get');
        $this->routeMatch->setParam("tilosRouter", true);
        $this->routeMatch->setParam("permission", "admin");
        $this->controller->setAccessDenied(false);


        $result = $this->controller->checkRouteAccess($this->event);
        $response = $this->controller->getResponse();
        //then

        $this->assertFalse($this->controller->getAccessDenied());

    }

    public function testAuthorAccessGuest()
    {
        $this->user = $this->em->find("\Radio\Entity\User", 1);
        //when
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'get');
        $this->routeMatch->setParam("tilosRouter", true);
        $this->routeMatch->setParam("permission", "guest");
        $this->controller->setAccessDenied(false);


        $result = $this->controller->checkRouteAccess($this->event);
        $response = $this->controller->getResponse();
        //then

        $this->assertFalse($this->controller->getAccessDenied());

    }


    public function testCustomFunction()
    {
        $this->user = $this->em->find("\Radio\Entity\User", 1);
        //when
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'get');
        $this->routeMatch->setParam("tilosRouter", true);
        $this->routeMatch->setParam("permission", "\RadioTest\Controller\BaseControllerTest::testAccess");
        $this->controller->setAccessDenied(false);


        $result = $this->controller->checkRouteAccess($this->event);
        $response = $this->controller->getResponse();
        //then

        $this->assertFalse($this->controller->getAccessDenied());

    }

    public function testCustomFunctionDeny()
    {
        $this->user = $this->em->find("\Radio\Entity\User", 1);
        //when
        $this->routeMatch->setParam('id', '1');
        $this->routeMatch->setParam('action', 'get');
        $this->routeMatch->setParam("tilosRouter", true);
        $this->routeMatch->setParam("permission", "\RadioTest\Controller\BaseControllerTest::testDeny");
        $this->controller->setAccessDenied(false);


        $result = $this->controller->checkRouteAccess($this->event);
        $response = $this->controller->getResponse();
        //then

        $this->assertTrue($this->controller->getAccessDenied());

    }


}

class BaseTestableController extends \Radio\Controller\BaseController
{
    private $accessDenied;

    public function accessDenied($event)
    {
        $this->accessDenied = true;
    }

    /**
     * @param mixed $accessDenied
     */
    public function setAccessDenied($accessDenied)
    {
        $this->accessDenied = $accessDenied;
    }

    /**
     * @return mixed
     */
    public function getAccessDenied()
    {
        return $this->accessDenied;
    }


}

?>