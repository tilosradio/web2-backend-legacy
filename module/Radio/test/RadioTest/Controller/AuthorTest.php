<?php

namespace RadioTest\Controller;

use HttpRequest;
use Radio\Controller\Auth;
use Radio\Controller\Author;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class AuthorTest extends TestBase {

    protected function setUp() {
        $this->initTest("Author", new Author());
    }

    public function testGetAuthor() {
        //when        
        $this->routeMatch->setParam('id', '936');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $author = $result->getVariables();
        var_dump($author);
        $this->assertContains("aktivista", $author['introduction']);
        $this->assertNotEmpty($author['photo']);
        $this->assertEquals(sizeof($author['urls']), 2);;

    }

    public function testGetWithAlias() {
        //when        
        $this->routeMatch->setParam('id', 'sztyepp');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        //then

        $author = $result->getVariables();

        //var_dump($author);
        $this->assertContains("uzginuver", $author['introduction']);
        $this->assertNotEmpty($author['photo']);

    }

}

?>