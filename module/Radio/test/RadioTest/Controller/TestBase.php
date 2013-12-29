<?php


namespace RadioTest\Controller;


use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use HttpRequest;
use Radio\Entity\Role;
use Radio\Entity\User;
use RadioTest\Fixitures\BaseData;
use Zend\Mail\Transport\File;
use Zend\Mail\Transport\FileOptions;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;
use RadioTest\Bootstrap;

class TestBase extends \PHPUnit_Framework_TestCase {

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
    protected $em;
    public $user;

    protected function initTest($controllerName, $controller) {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = $controller;;
        $this->request = new \Zend\Http\PhpEnvironment\Request();
        $this->routeMatch = new RouteMatch(array('controller' => $controllerName));
        $this->event = new MvcEvent();

        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
        $this->em = $serviceManager->get('doctrine.entitymanager.orm_default');

        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('doctrine.authenticationservice.orm_default', $this);

        $transport = new File();
        $options = new FileOptions(array(
            'path' => '/tmp/',
        ));
        $transport->setOptions($options);
        $serviceManager->setService("Radio\Mail\Transport", $transport);
    }

    public function hasIdentity() {
        return $this->user != null;
    }

    public function getIdentity() {
        return $this->user;
    }

    public function createUser($id, $name, $role) {
        $u = new User();
        $u->setUsername($name);
        $u->setId($id);
        $r = new Role();
        $r->setId(1);
        $r->setName($role);
        $u->setRole($r);
        return $u;
    }

    public function baseData() {
        $loader = new Loader();
        $loader->addFixture(new BaseData());
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }

} 