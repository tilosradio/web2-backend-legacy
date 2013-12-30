<?php
namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\JsonModel,
    Radio\Provider\AuthService,
    Zend\Json\Json;


class Auth extends AbstractActionController {

    use AuthService;

    public function loginAction() {
        if (!$this->getRequest()->isPost()) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array("error" => "Bad request"));
        }
        $data = Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY);
        if (!array_key_exists('username', $data) || !array_key_exists('username', $data)) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array("error" => "Bad request: User and password is required."));
        }

        $adapter = $this->getAuthService()->getAdapter();
        $adapter->setIdentityValue($data['username']);
        $adapter->setCredentialValue($data['password']);
        $result = $adapter->authenticate();
        if ($result->isValid()) {
            $this->getAuthService()
                ->getStorage()
                ->write($result->getIdentity());
            return $this->success();
        } else
            $this->getResponse()->setStatusCode(401);
        return new JsonModel(array('success' => false, 'error' => "Authentication error"));
    }

    public function logoutAction() {
        if (!$this->getAuthService()->hasIdentity()) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array('success' => false, 'error' => "No valid session"));
        }
        $this->getAuthService()->clearIdentity();
        return $this->success();
    }

    private function success() {
        $identity = $this->getAuthService()->getIdentity();
        // identity shall never be null on success
        if (null !== $identity)
            $identity = $identity->toArraySafe();
        return new JsonModel(array('success' => true, 'data' => $identity));
    }

    private function failed($msg) {
        return new JsonModel(array('success' => false, 'error' => $msg));
    }
}
