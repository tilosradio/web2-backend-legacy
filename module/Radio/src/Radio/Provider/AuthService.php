<?php
namespace Radio\Provider;

trait AuthService 
{
    private function getAuthService() {
        static $as = null;
        if (null === $as)
            $as = $this->getServiceLocator()->get('doctrine.authenticationservice.orm_default');
        return $as;
    }
}
