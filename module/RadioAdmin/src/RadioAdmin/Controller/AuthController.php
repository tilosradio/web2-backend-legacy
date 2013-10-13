<?php
namespace RadioAdmin\Controller;

use Zend\Authentication\Result;
use Zend\View\Model\ViewModel;

class AuthController extends BaseController
{
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
        $okay = $result->getCode() === Result::SUCCESS;
        return $this->redirect()->toRoute($okay ? self::LOGIN_REDIRECTS_TO : self::LOGOUT_REDIRECTS_TO);
    }
    
    public function logoutAction()
    {
        $this->getAuthService()->clearIdentity();
        return $this->redirect()->toRoute(self::LOGOUT_REDIRECTS_TO);
    }    
}