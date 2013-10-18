<?php

namespace Radio;

use Zend\Mvc\MvcEvent,
    Radio\Entity\Role,
    Radio\Permissions\Acl,
    Radio\Permissions\RoleAssertion,
    Radio\Permissions\PermissionException;

class Module {

    public function onBootstrap(MvcEvent $event) {
        $em = $event->getApplication()->getEventManager();
	$em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'));
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig() {
        return array();
    }

    /**
     * Permission control
     * 
     * @param MvcEvent $event
     */
    public function preDispatch(MvcEvent $event) {
        $serviceManager = $event->getApplication()
                                ->getServiceManager();
        $authService = $serviceManager->get('doctrine.authenticationservice.orm_default');
        if ($authService->hasIdentity())
        {
            // get the role of the authenticated user
            $user = $authService->getIdentity();
            $role = $user->getRole();
        } else
        {
            // unauthenticated users have the default role ('guest')
            $user = null;
            $role = Role::getDefault();
        }

        // get requested resource
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
        $recordId = $routeMatch->getParam('id');

        // initialize permission check
        $assertion = new RoleAssertion($user, $recordId);
        $assertion->setServiceLocator($serviceManager);
        try {
            $acl = new Acl($this->getPermissionsConfig(), $assertion);
            // check user permissions
            if (!$acl->hasResource($controller) || !$acl->isAllowed($role->getName(), $controller, $action)) {
                // respond with 401 Unauthorized
                $event->getResponse()
                      ->setStatusCode(401)
                      ->sendHeaders();
                die();
            }
        } catch (PermissionException $pe)
        {
            // configuration error
            $event->getResponse()
                  ->setStatusCode(500)
                  ->sendHeaders();
            die($pe->getMessage());
        }
    }

    private function getPermissionsConfig() {
        // TODO: load roles from database
        return include __DIR__ . '/config/permissions.config.php';
    }
}