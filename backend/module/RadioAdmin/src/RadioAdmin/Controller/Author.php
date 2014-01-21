<?php

namespace RadioAdmin\Controller;

use Radio\Mapper\Field;
use Radio\Mapper\ObjectFieldSetter;
use Radio\Mapper\ObjectMapper;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;
use Radio\Mapper\MapperFactory;

/**
 * @SWG\Resource(resourcePath="/author",basePath="/api")
 */
class Author extends \Radio\Controller\BaseController {

    use EntityManager;

    public function create($data) {
        try {
            // validation
            if ( !isset($data['name']) || !isset($data['photo']) ||
                            !isset($data['avatar']) || !isset($data['introduction']) ||
                            !isset($data['user_id']) ) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Mandatory fields: name, photo, avatar, introduction, user_id."));
            }

            // validate user id
            if ( !is_numeric($data['user_id']) ) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "User id must be numeric."));
            }
            $user = $this->getEntityManager()->find('Radio\Entity\User', $data['user_id']);
            if ( is_null($user) )  {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "User id does not exist in DB."));
            }
            // check that user is not yet assigned to author
            $check_author = $this->getEntityManager()
                    ->getRepository('Radio\Entity\Author')
                    ->findOneBy(array('user' => $data['user_id']));

            if ( !is_null($check_author) ) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "User is already assigned to another author."));
            }

            $author = new \Radio\Entity\Author();

            $author->setName($data['name']);
            $author->setPhoto($data['photo']);
            $author->setAvatar($data['avatar']);
            $author->setIntroduction($data['introduction']);
            $author->setUser($user);
            if (! is_null($data['alias']) )
                $author->setAlias($data['alias']);

    	    $this->getEntityManager()->persist($author);
    	    $this->getEntityManager()->flush();

    	    return new JsonModel(array("create"=>"success"));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function update($e) {
        try {
            $id = $this->params()->fromRoute("id");
            $data = $this->getRawData($e);

            $author = $this->getEntityManager()->find("\Radio\Entity\Author", $id);

            $mapper = new ObjectMapper(new ObjectFieldSetter());
            $f = new Field("name");
            $mapper->addMapper($f->required());
            $mapper->addMapper(new Field("description"));
            $mapper->map($data, $author);

            $this->getEntityManager()->flush();
            return new JsonModel(array("success" => true));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function delete($id) {
        try {
            $author = $this->getEntityManager()->find('Radio\Entity\Author', $id);
            if ( is_null($author) ) {
                return new JsonModel(array("error" => "Author does not exist in DB."));
            }

            $this->getEntityManager()->remove($author);
            $this->getEntityManager()->flush();

            return new JsonModel(array("delete"=>"success"));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

}
