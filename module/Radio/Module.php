<?php

namespace Radio;

use Radio\Util\BusinessLogger;
use Zend\Mvc\MvcEvent,
    Radio\Entity\Role,
    Radio\Permissions\Acl,
    Radio\Permissions\RoleAssertion,
    Radio\Permissions\PermissionException;

class Module {

    public function onBootstrap(MvcEvent $event) {
        $em = $event->getApplication()->getEventManager();
        //$em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 10);
        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'logger'), 20);

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
        return array(
            'invokables' => array(
                'ApiAuditLogger' => '\Radio\Util\ApiAuditLogger'
            )
        );
    }

    /**
     * Permission control
     *
     * @param MvcEvent $event
     */
    public function logger(MvcEvent $event) {
        $serviceManager = $event->getApplication()->getServiceManager();
        $authService = $serviceManager->get('doctrine.authenticationservice.orm_default');

        $user = $authService->hasIdentity() ? $authService->getIdentity()->getUsername() : "unknown";

        $method = $event->getRequest()->getMethod();
        if ($method != "GET") {
            $url = $event->getRequest()->getRequestUri();
            $params = "";
            if ($method == "POST") {
                $params = $this->implode($event->getRequest()->getPost());
            }
            $bl = $event->getApplication()->getServiceManager()->get("ApiAuditLogger");
            $bl->log($user, $url, $method, $params);
        }

    }

    private function implode($arr) {
        $res = "";
        if (isset($arr)) {
            $res .= json_encode($arr);
        }
        return $res;
    }

    /**
     * Permission control
     *
     * @param MvcEvent $event
     */
    public function preDispatch(MvcEvent $event) {
        $serviceManager = $event->getApplication()->getServiceManager();
        $authService = $serviceManager->get('doctrine.authenticationservice.orm_default');
        // identify the user
        $user = $authService->hasIdentity() ? $authService->getIdentity() : null;
        $role = empty($user) ? Role::getDefault() : $user->getRole();
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
                if (!$acl->hasResource($controller))
                    die("ERROR: No permission rule for $controller");
                else if (!$acl->isAllowed($role->getName(), $controller, $action))
                    die('ERROR: Unauthorized');
            }
        } catch (PermissionException $pe) {
            // configuration error
            $event->getResponse()
                ->setStatusCode(500)
                ->sendHeaders();
            die($pe->getMessage());
        }
    }

    private function getPermissionsConfig() {
        return include __DIR__ . '/config/permissions.config.php';
    }
}