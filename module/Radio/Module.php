<?php

namespace Radio;

use Zend\Mvc\MvcEvent,
    Radio\Permissions\Acl;

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
    public function preDispatch(MvcEvent $event)
    {
        /* 
         * TODO: get role associated with the user (implement association mechanism)
        $as = $event->getApplication()
                    ->getServiceManager()
                    ->get('doctrine.authenticationservice.orm_default');
        $user = $as->hasIdentity() ? $as->getIdentity() : null;
         */
        $role = Acl::DEFAULT_ROLE;
        
        // get requested resource
        $rm = $event->getRouteMatch();
        $controller = $rm->getParam('controller');
        $action = $rm->getParam('action');
        $recordId = $rm->getParam('id');
        
        // initialize permission check
        $acl = new Acl($this->getPermissionsConfig(), $recordId);
        // check user permissions
        if (!$acl->hasResource($controller) || !$acl->isAllowed($role, $controller, $action))
        {
            // respond with 401 Unauthorized
            $event->getResponse()
                  ->setStatusCode(401)
                  ->sendHeaders();
            die();
        }
    }
    
    private function getPermissionsConfig()
    {
        return include __DIR__ . '/config/permissions.config.php';
    }
}