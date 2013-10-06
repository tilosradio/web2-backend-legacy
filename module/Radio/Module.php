<?php

namespace Radio;

use Zend\Db\ResultSet\ResultSet;
use Radio\Model\Show;
use Radio\Model\Author;
use Zend\Db\TableGateway\TableGateway;
use Radio\Model\ShowTable;
use Radio\Model\AuthorTable;

class Module {

    public function onBootstrap($e) {
        
    }

    public function getAutoloaderConfig() {
        return array('Zend\Loader\ClassMapAutoloader' => array(__DIR__ . '/autoload_classmap.php',), 'Zend\Loader\StandardAutoloader' => array('namespaces' => array(__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,),),);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig() {
        return array();
    }

}
