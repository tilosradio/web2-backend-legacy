<?php

namespace RadioAdmin\Controller;

use Doctrine\Tests\Common\Persistence\Mapping\ChildEntity;
use Radio\Mapper\ChildCollection;
use Radio\Mapper\ChildObject;
use Radio\Mapper\Field;
use Radio\Mapper\ObjectFieldSetter;
use Radio\Mapper\ObjectMapper;
use Radio\Mapper\Validator\Min;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;
use Radio\Entity\ChangePasswordToken;
use Zend\XmlRpc\Value\DateTime;
use Zend\Mail;
use Radio\Controller\BaseController;


/**
 * @SWG\Resource(resourcePath="/user",basePath="/api")
 */
class User extends BaseController
{

    use EntityManager;

    public function createConverter()
    {
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
    public function get($e)
    {
        $id = $this->params()->fromRoute("id");

        if ($id == 'me') {
            return $this->currentUserAction($e);
        }


        return $this->findUser($id);
    }

    public function findUser($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u', 'a', 'c', 's', 'r')->from('\Radio\Entity\User', 'u');
        $qb->leftJoin('u.author', 'a');
        $qb->leftJoin('a.contributions', 'c');
        $qb->leftJoin('c.show', 's');
        $qb->leftJoin('u.role', 'r');
        $qb->where("u.id = :id");
        $q = $qb->getQuery();
        $q->setParameter("id", $id);

        $m = new ObjectMapper();
        $m->addMapper(new Field("id"));
        $m->addMapper(new Field("username"));
        if ($this->isAdmin() || $this->getCurrentUser()->getId() == $id) {
            $m->addMapper(new Field("email"));
        }

        $a = $m->addMapper(new ChildObject("author"));
        $a->addMapper(new Field("id"));
        $a->addMapper(new Field("name"));
        $a->addMapper(new Field("alias"));

        $c = $a->addMapper(new ChildCollection("contributions"));
        $c->addMapper(new Field("nick"));

        $s = $c->addMapper(new ChildObject("show"));
        $s->addMapper(new Field("id"));
        $s->addMapper(new Field("name"));
        $s->addMapper(new Field("alias"));

        $r = $m->addMapper(new ChildObject("role"));
        $r->addMapper(new Field("name"));
        $r->addMapper(new Field("id"));


        $result = [];
        $m->map($q->getArrayResult()[0], $result);
        return new JsonModel($result);
    }

    public function currentUserAction($e)
    {
        $authService = $this->getServiceLocator()->get('doctrine.authenticationservice.orm_default');
        // identify the user
        $user = $authService->hasIdentity() ? $authService->getIdentity() : null;
        if ($user == null) {
            return new JsonModel(array());
        }
        return $this->findUser($user->getId());

    }


    public function update($e)
    {
        try {
            $id = $this->params()->fromRoute("id");
            $data = $this->getRawData($e);

            $user = $this->getEntityManager()->find("\Radio\Entity\User", $id);

            if (!$user) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(array("error" => "User is not found"));
            }

            $mapper = new ObjectMapper(new ObjectFieldSetter($this->getEntityManager()));
            $mapper->addMapper(Field::of("email")->required());
            $mapper->addMapper(Field::of("password")->valid(new Min(9)));
            if ($this->isAdmin()) {
                $mapper->addMapper(new ChildObject("role", "\Radio\Entity\Role"));
            }

            $mapper->map($data, $user);

            $this->getEntityManager()->flush();
            return new JsonModel(array("success" => true));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }


}