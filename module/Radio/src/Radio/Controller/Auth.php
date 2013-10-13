<?php
namespace Radio\Controller;

use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class Auth extends AbstractActionController
{
    public function loginAction()
    {
        if (!$this->getRequest()->isPost())
        {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array("error" => "Bad request"));
        }
        $adapter = $this->getAuthService()->getAdapter();
        $adapter->setIdentityValue($this->params()->fromPost('username'));
        $adapter->setCredentialValue($this->params()->fromPost('password'));
        $result = $adapter->authenticate();
        if ($result->isValid())
        {
            $this->getAuthService()
                 ->getStorage()
                 ->write($result->getIdentity());
            return $this->success();
        } else
            return $this->failed();
    }
    
    public function logoutAction()
    {
        if (!$this->getAuthService()->hasIdentity())
            return $this->failed();
        $this->getAuthService()->clearIdentity();
        return $this->success();
    }
    
    private function getAuthService()
    {
        static $as = null;
        if (null === $as)
            $as = $this->getServiceLocator()->get('doctrine.authenticationservice.orm_default');
        return $as;
    }
    
    private function success()
    {
        $identity = $this->getAuthService()->getIdentity();
        if (null !== $identity)
        {
            $identity = $identity->toArray();
            unset($identity['password']);
            unset($identity['salt']);
        }
        return new JsonModel(array('success' => true, 'identity' => $identity));
    }
    
    private function failed()
    {
        return new JsonModel(array('success' => false));
    }
}