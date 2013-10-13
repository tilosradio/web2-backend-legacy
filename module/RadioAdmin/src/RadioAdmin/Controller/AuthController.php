<?php
namespace RadioAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\View\Model\ViewModel;

class AuthController extends BaseController
{
    const LOGIN_REDIRECTS_TO = 'success';
    const LOGOUT_REDIRECTS_TO = 'login';
    
    public function loginAction()
    {
        if ($this->getAuthService()->hasIdentity())
            // user has already logged in
            return $this->redirect()->toRoute(self::LOGIN_REDIRECTS_TO);
        return new ViewModel();
    }
    
    public function authenticateAction()
    {
        if (!$this->getRequest()->isPost())
            return $this->redirect()->toRoute(self::LOGOUT_REDIRECTS_TO);
        $as = $this->getAuthService();
        $as->getAdapter()->setIdentityValue($this->params()->fromPost('username'));
        $as->getAdapter()->setCredentialValue($this->params()->fromPost('password'));
        $result = $as->authenticate();
        var_dump($result);
        die();
    }
    
    public function logoutAction()
    {
        $this->getAuthService()->clearIdentity();
        return $this->redirect()->toRoute(self::LOGOUT_REDIRECTS_TO);
    }    
}