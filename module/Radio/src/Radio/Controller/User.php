!<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;
use Radio\Entity\ChangePasswordToken;
use Zend\XmlRpc\Value\DateTime;
use Zend\Mail;

/**
 * @SWG\Resource(resourcePath="/user",basePath="/api")
 */
class User extends BaseController {

    use EntityManager;

    public function createConverter() {
        return function ($result) {
            $user = $result;
            $retUser['id'] = $user->getId();
            $retUser['role'] = $user->getRole();
            $retUser['username'] = $user->getUsername();
            $retUser['email'] = $user->getEmail();

            return $retUser;
        };
    }

    /**
     * @SWG\Api(
     *   path="/user",
     *   description="Function related to the users of the radio",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="List all active user"
     *   )
     * )
     */
    public function getList() {
        return $this->getEntityList("\Radio\Entity\User", $this->createConverter());
    }

    /**
     * @SWG\Api(
     *   path="/user/{id}",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="Return information about a specific user",
     *     @SWG\Parameters(
     *        @SWG\Parameter(
     *           name= "id",
     *           description="Id of the author",
     *           paramType="path",
     *           type="string"
     *        )
     *     )
     *   )
     * )
     */
    public function get($id) {
        if ($id == 'me') {
            return $this->currentUserAction();
        }


        return $this->getEntity("\Radio\Entity\User", $id, $this->createConverter());
    }

    public function currentUserAction() {
        $authService = $this->getServiceLocator()->get('doctrine.authenticationservice.orm_default');
        // identify the user
        $user = $authService->hasIdentity() ? $authService->getIdentity() : null;
        if ($user) {
            $u = [];
            $u['username'] = $user->getUsername();
            $u['role'] = ['name' => $user->getRole()->getName()];
            return new JsonModel($u);
        } else {
            return new JsonModel(array());
        }

    }

    public function create($data) {
        try {
            // validation
            if (!isset($data['role_id']) || !isset($data['username']) ||
                !isset($data['password']) || !isset($data['email'])
            ) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Mandatory fields: role_id, username, password, email."));
            }

            // validate fields via DB
            // check if role exist
            $role = $this->getEntityManager()->find('Radio\Entity\Role', $data['role_id']);
            if (is_null($role)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "No such existing role."));
            }
            // check if username taken
            $check_user = $this->getEntityManager()
                ->getRepository('Radio\Entity\User')
                ->findOneBy(array('username' => $data['username']));
            if (!is_null($check_user)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Username already taken."));
            }
            // check if email taken
            $check_user = $this->getEntityManager()
                ->getRepository('Radio\Entity\User')
                ->findOneBy(array('email' => $data['email']));
            if (!is_null($check_user)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Email already taken."));
            }

            $user = new \Radio\Entity\User();

            $user->setUsername($data['username']);
            $user->setEmail($data['email']);
            $user->createSalt();
            $user->setPassword($data['password']);
            $user->setRole($role);

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();

            return new JsonModel(array("create" => "success"));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function update($id, $data) {
        try {
            $user = $this->getEntityManager()->find('Radio\Entity\User', $id);

            // validation
            if (is_null($user)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "User id does not exist."));
            }

            if (!isset($data['role_id']) && !isset($data['username']) &&
                !isset($data['password']) && !isset($data['email'])
            ) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "One of the following fields must exist: role_id, username, password, email."));
            }

            // validate fields via DB
            // check if role exist
            $role = null;
            if (isset($data['role_id'])) {
                $role = $this->getEntityManager()->find('Radio\Entity\Role', $data['role_id']);
                if (is_null($role)) {
                    $this->getResponse()->setStatusCode(400);
                    return new JsonModel(array("error" => "No such existing role."));
                }
            }
            // check if username taken
            if (isset($data['username']) && $user->getUsername() != $data['username']) {
                $check_user = $this->getEntityManager()
                    ->getRepository('Radio\Entity\User')
                    ->findOneBy(array('username' => $data['username']));
                if (!is_null($check_user)) {
                    $this->getResponse()->setStatusCode(400);
                    return new JsonModel(array("error" => "Username already taken."));
                }
            }
            // check if email taken
            if (isset($data['email']) && $user->getEmail() != $data['email']) {
                $check_user = $this->getEntityManager()
                    ->getRepository('Radio\Entity\User')
                    ->findOneBy(array('email' => $data['email']));
                if (!is_null($check_user)) {
                    $this->getResponse()->setStatusCode(400);
                    return new JsonModel(array("error" => "Email already taken."));
                }
            }

            $updated = "";
            if (!is_null($role)) {
                $user->setRole($role);
                $updated .= " Role: " . $data['role_id'];
            }
            if (isset($data['username'])) {
                $user->setUsername($data['username']);
                $updated .= " Username: " . $data['username'];
            }
            if (isset($data['email'])) {
                $user->setEmail($data['email']);
                $updated .= " Email: " . $data['email'];
            }
            if (isset($data['password'])) {
                $user->setPassword($data['password']);
                $updated .= " Password: *****:)**";
            }

            $this->getEntityManager()->flush();
            return new JsonModel(array("update" => "success", "Updated values" => $updated));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function delete($id) {
        try {
            $user = $this->getEntityManager()->find('Radio\Entity\User', $id);
            if (is_null($user)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "User does not exist in DB."));
            }

            $this->getEntityManager()->remove($user);
            $this->getEntityManager()->flush();

            return new JsonModel(array("delete" => "success"));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }


}