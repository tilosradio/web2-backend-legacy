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
class Author extends \Radio\Controller\BaseController
{

    use EntityManager;

    public function create($e)
    {
        try {
            // validation
            $data = $this->getRawData($e);


            $author = new \Radio\Entity\Author();
            $author->setPhoto("");
            $author->setEmail("");
            $author->setAlias("");
            $author->setAvatar("");

            $mapper = new ObjectMapper(new ObjectFieldSetter());
            $f = new Field("name");
            $mapper->addMapper($f->required());
            $mapper->addMapper(new Field("introduction"));

            $mapper->map($data, $author);
            $this->getEntityManager()->persist($author);
            $this->getEntityManager()->flush();
            return new JsonModel(array("success" => true, "data" => array("id" => $author->getId())));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function update($e)
    {
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

    public function delete($id)
    {
        try {
            $author = $this->getEntityManager()->find('Radio\Entity\Author', $id);
            if (is_null($author)) {
                return new JsonModel(array("error" => "Author does not exist in DB."));
            }

            $this->getEntityManager()->remove($author);
            $this->getEntityManager()->flush();

            return new JsonModel(array("delete" => "success"));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

}
