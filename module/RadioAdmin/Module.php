<?php

namespace RadioAdmin;

use Radio\Util\BusinessLogger;
use Zend\Mail\Transport\Sendmail;
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
            ),
            'services' => array(
                '\Radio\Mail\Transport' => new Sendmail()
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


        $method = $event->getRequest()->getMethod();
        if ($method != "GET") {
            $user = $authService->hasIdentity() ? $authService->getIdentity()->getUsername() : "unknown";
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

    
}