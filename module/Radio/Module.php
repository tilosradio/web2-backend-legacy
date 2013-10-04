<?php
namespace Radio;

use Zend\Db\ResultSet\ResultSet;
use Radio\Model\Show;
use Zend\Db\TableGateway\TableGateway;
use Radio\Model\ShowTable;

class Module {
    public function onBootstrap($e) {
        //TODO define a clean error handling
        $logger = new \Zend\Log\Logger();
        //$writer = new \Zend\Log\Writer\FirePhp();
        $writer = new \Zend\Log\Writer\Stream(__DIR__ . '/error.log');
        $logger->addWriter($writer, 0);
        $logger->registerErrorHandler($logger);
        $logger->registerExceptionHandler($logger);
        $logger->info('Custom logger initilized' . __FILE__ . __LINE__);
        register_shutdown_function(function () use ($logger) {
            if ($e = error_get_last()) {
                $logger->ERR($e['message'] . " in " . $e['file'] . ' line ' . $e['line']);
                $logger->__destruct();
            }
        });
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
                'Radio\Model\ShowTable' =>  function($sm) {
                    $tableGateway = $sm->get('ShowTableGateway');
                    $table = new ShowTable($tableGateway);
                    return $table;
                },
                'ShowTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Show());
                    return new TableGateway('show', $dbAdapter, null, $resultSetPrototype);
                },)
           );

    }
}
