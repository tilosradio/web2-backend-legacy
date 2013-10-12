<?php
namespace RadioAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    const LOGIN_REDIRECTS_TO = 'success';
    const LOGOUT_REDIRECTS_TO = 'login';
    
    public function loginAction()
    {
        if ($this->getAuthService()->hasIdentity())
            // user has already logged in
            return $this->redirect()->toRoute(self::LOGIN_REDIRECTS_TO);
        $adapter = $this->getAuthAdapter();
        return new ViewModel();
    }
    
    public function authenticateAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost())
            return $this->redirect()->toRoute(self::LOGOUT_REDIRECTS_TO);
    }
    
    public function logoutAction()
    {
        $this->getAuthService()->clearIdentity();
        return $this->redirect()->toRoute(self::LOGOUT_REDIRECTS_TO);
    }
    
    private function getAuthAdapter()
    {
        static $adapter = null;
        if (null === $adapter)
            $adapter = $this->getServiceManager()->get('doctrine.authentication.ormdefault');
        return $adapter;
    }        
    
    /**
     * @return AuthService
     */
    private function getAuthService()
    {
        static $as = null;
        if (null === $as)
            $as = $this->getServiceLocator()->get('AuthService');
        return $as;
    }
}