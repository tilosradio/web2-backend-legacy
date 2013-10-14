<?php
namespace Radio\Provider;

use Zend\ServiceManager\ServiceLocatorInterface;

trait ServiceLocator
{
    private $services = null;
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
         $this->services = $serviceLocator;
    }

    public function getServiceLocator()
    {
         return $this->services;
    }    
}