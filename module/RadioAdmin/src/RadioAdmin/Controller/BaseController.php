<?php
namespace RadioAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class BaseController extends AbstractActionController
{
    const LOGIN_REDIRECTS_TO = 'home';
    const LOGOUT_REDIRECTS_TO = 'sign_in';
    
    public function init()
    {
        if (!$this->isLoggedIn())
        {
            $this->redirect()->toRoute('sign_in');
            return;
        }
    }
    
    protected function isLoggedIn()
    {
        return !empty($this->getAuthService()->getIdentity());
    }
    
    protected function getAuthService()
    {
        static $as = null;
        if (null === $as)
            $as = $this->getServiceLocator()->get('doctrine.authenticationservice.orm_default');
        return $as;
    }
}