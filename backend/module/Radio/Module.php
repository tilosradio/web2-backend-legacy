<?php

namespace Radio;

use Radio\Util\BusinessLogger;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mvc\MvcEvent,
    Radio\Entity\Role,
    Radio\Permissions\Acl,
    Radio\Permissions\RoleAssertion,
    Radio\Permissions\PermissionException;

class Module
{


    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        $mailTransport = new SmtpTransport();
	$options = new SmtpOptions(array(
                'name' => 'localhost',
                'host' => '127.0.0.1',
                'port' => 25,
        ));
        $mailTransport->setOptions($options);
	return array(
            'invokables' => array(
                'ApiAuditLogger' => '\Radio\Util\ApiAuditLogger'
            ),
            'services' => array(
                '\Radio\Mail\Transport' => $mailTransport
            )
        );
    }


    private function implode($arr)
    {
        $res = "";
        if (isset($arr)) {
            $res .= json_encode($arr);
        }
        return $res;
    }


}
