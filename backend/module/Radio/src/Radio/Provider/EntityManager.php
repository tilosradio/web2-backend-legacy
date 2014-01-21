<?php
namespace Radio\Provider;

trait EntityManager 
{
    private function getEntityManager() {
        static $em = null;
        if (null === $em)
            $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        return $em;
    }
}