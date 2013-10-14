<?php
namespace Radio\Controller;

use Zend\Authentication\Result,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\JsonModel,
    Radio\Provider\AuthService;

class Auth extends AbstractActionController
{
    use AuthService;
    
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
    
    private function success()
    {
        $identity = $this->getAuthService()->getIdentity();
        // identity shall never be null on success
        if (null !== $identity)
            $identity = $identity->toArraySafe();
        return new JsonModel(array('success' => true, 'identity' => $identity));
    }
    
    private function failed()
    {
        return new JsonModel(array('success' => false));
    }
}