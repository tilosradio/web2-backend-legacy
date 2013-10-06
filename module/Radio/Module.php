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
        return array(
            'factories' => array(
                'Radio\Model\ShowTable' => function($sm) {
                    $tableGateway = $sm->get('ShowTableGateway');
                    $table = new ShowTable($tableGateway);
                    return $table;
                },
                'Radio\Model\AuthorTable' => function($sm) {
                    $tableGateway = $sm->get('AuthorTableGateway');
                    $table = new AuthorTable($tableGateway);
                    return $table;
                },
                'AuthorTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Author());
                    return new TableGateway('author', $dbAdapter, null, $resultSetPrototype);
                },
                'ShowTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Show());
                    return new TableGateway('radioshow', $dbAdapter, null, $resultSetPrototype);
                },)
        );
    }

}
