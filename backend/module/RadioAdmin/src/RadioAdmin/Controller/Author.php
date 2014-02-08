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


    public function get($e)
    {
        $id = $this->getIdentifier($e->getRouteMatch(), $e->getRequest());

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a', 'sa', 's', 'u')->from('\Radio\Entity\Author', 'a');
        if (is_numeric($id)) {
            $qb->where('a.id = :id');
        } else {
            $qb->where('a.alias = :id');
        }
        $qb->leftJoin('a.contributions', 'sa')->leftJoin('sa.show', 's')->leftJoin('a.urls', 'u');

        $q = $qb->getQuery();
        $q->setParameter("id", $id);
        $result = $q->getArrayResult()[0];

        $r = [];
        $mapper = MapperFactory::authorElementMapper(['baseUrl' => $this->getServerUrl()]);
        if ($this->isAdmin()) {
            $mapper->addMapper(new Field("email"));
        }
        $mapper->map($result, $r);
        return new JsonModel($r);


    }

    public function create($e)
    {
        try {
            // validation
            $data = $this->getRawData($e);


            $author = new \Radio\Entity\Author();
            $author->setPhoto("");
            $author->setAvatar("");

            $mapper = new ObjectMapper(new ObjectFieldSetter());

            $f = new Field("name");
            $mapper->addMapper($f->required());

            $f = new Field("alias");
            $mapper->addMapper($f->required());

            $f = new Field("email");
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
            if ($this->isAdmin()) {
                $f = new Field("alias");
                $mapper->addMapper($f->required());

                $f = new Field("email");
                $mapper->addMapper($f->required());
            }
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
