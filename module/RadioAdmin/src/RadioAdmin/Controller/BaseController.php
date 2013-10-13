<?php
namespace RadioAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class BaseController extends AbstractActionController
{
    protected function getAuthService()
    {
        static $as = null;
        if (null === $as)
            $as = $this->getServiceLocator()->get('doctrine.authenticationservice.orm_default');
        return $as;
    }
}