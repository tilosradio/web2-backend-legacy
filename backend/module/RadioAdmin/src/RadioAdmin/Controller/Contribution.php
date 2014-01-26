<?php

namespace RadioAdmin\Controller;

use DoctrineORMModule\Proxy\__CG__\Radio\Entity\TextContent;
use Radio\Mapper\ArrayFieldSetter;
use Radio\Mapper\Field;
use Radio\Mapper\ChildObject;
use Radio\Mapper\ObjectFieldSetter;
use Radio\Mapper\ObjectMapper;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;
use Radio\Mapper\MapperFactory;


class Contribution extends \Radio\Controller\BaseController
{

    use EntityManager;

    public function get($e)
    {

        $id = $this->params()->fromRoute("id");
        return $this->getEntity("\Radio\Entity\Contribution", $id);

    }

    public function mapEntity($result)
    {
        $m = new ObjectMapper();
        $m->addMapper(new Field("id"));
        $m->addMapper(new Field("nick"));
        $am = $m->addMapper(new ChildObject("author"));
        $sm = $m->addMapper(new ChildObject("show"));
        $am->addMapper(new Field("name"));
        $am->addMapper(new Field("id"));

        $sm->addMapper(new Field("name"));
        $sm->addMapper(new Field("id"));

        $to = [];
        $m->map($result, $to, new ArrayFieldSetter());
        return $to;
    }

    public function findEntityObject($type, $id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s', 'c', 'a')->from('\Radio\Entity\Contribution', 'c')->where("c.id = :id");
        $qb->leftJoin("c.author", "a");
        $qb->leftJoin("c.show", "s");
        $q = $qb->getQuery();
        $q->setParameter("id", $id);
        return $q->getArrayResult()[0];
    }

    public function create($e)
    {
        try {
            $data = $this->getRawData($e);

            $mapper = new ObjectMapper(new ObjectFieldSetter($this->getEntityManager()));
            $mapper->addMapper(new Field("nick"));
            $mapper->addMapper(new ChildObject("show", "\Radio\Entity\Show"));
            $mapper->addMapper(new ChildObject("author", "\Radio\Entity\Author"));


            $show = new \Radio\Entity\Contribution();
            $mapper->map($data, $show);

            $this->getEntityManager()->persist($show);
            $this->getEntityManager()->flush();
            return new JsonModel(array("success" => true, "data" => array("id" => $show->getId())));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function delete($e)
    {
        try {
            $id = $this->params()->fromRoute("id");
            $contribution = $this->getEntityManager()->find('Radio\Entity\Contribution', $id);
            if (is_null($contribution)) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(array("error" => "Contribution does not exist in DB."));
            }

            $this->getEntityManager()->remove($contribution);
            $this->getEntityManager()->flush();

            return new JsonModel(array("success" => "true"));

        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }


}
