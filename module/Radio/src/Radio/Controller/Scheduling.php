<?php

namespace Radio\Controller;

use Radio\Mapper\ArrayFieldSetter;
use Radio\Mapper\DateField;
use Radio\Mapper\Field;
use Radio\Mapper\ListMapper;
use Radio\Mapper\ObjectMapper;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;
use Radio\Mapper\MapperFactory;


class Scheduling extends BaseController
{

    use EntityManager;


    public function getList()
    {
        return $this->getEntityList("\Radio\Entity\Scheduling");
    }


    public function get($id)
    {
        return $this->getEntity("\Radio\Entity\Scheduling", $id);

    }

    public function mapEntity($result)
    {
        $m = $this->createEntityListMapper();
        $to = [];
        $m->map($result, $to, new ArrayFieldSetter());
        return $to;
    }

    public function createEntityListMapper()
    {
        $m = new ObjectMapper();
        $m->addMapper(new Field("id"));
        $m->addMapper(new Field("weekType"));
        $m->addMapper(new Field("weekDay"));
        $m->addMapper(new Field("hourFrom"));
        $m->addMapper(new Field("minFrom"));
        $m->addMapper(new Field("duration"));
        $m->addMapper(new DateField("validFrom"));
        $m->addMapper(new DateField("validTo"));
        $m->addMapper(new DateField("base"));
        return $m;
    }

    public function mapEntityListElement($result)
    {
        $m = $this->createEntityListMapper();
        $to = [];
        $m->map($result, $to, new ArrayFieldSetter());
        return $to;
    }


    public function findEntityList($type)
    {
        $showId = $this->params()->fromRoute("show");
        $show = $this->getEntityManager()->find("\Radio\Entity\Show", $showId);
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s')->from('\Radio\Entity\Scheduling', 's')->where("s.show = :show")->orderBy("s.validFrom", "ASC");
        $q = $qb->getQuery();
        $q->setParameter("show", $show);
        return $q->getArrayResult();

    }

    public function findEntityObject($type, $id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s')->from('\Radio\Entity\Scheduling', 's')->where("s.id = :id");
        $q = $qb->getQuery();
        $q->setParameter("id", $id);
        return $q->getArrayResult()[0];
    }


}
