<?php

namespace Radio\Util;


use Radio\Entity\ApiAudit;
use Radio\Provider\EntityManager;
use Zend\Json\Server\Smd\Service;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApiAuditLogger implements ServiceLocatorAwareInterface {

    private $service;

    use EntityManager;

    function log($user, $url, $method, $parms) {
        $log = new ApiAudit();
        $log->setUser($user);
        $log->setUrl($url);
        $log->setPostParams($parms);
        $log->setCallDate(new \DateTime());
        $log->setMethod($method);
        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->flush();
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->service = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->service;
    }
}