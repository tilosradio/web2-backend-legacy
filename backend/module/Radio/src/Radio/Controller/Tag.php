<?php

namespace Radio\Controller;


use Radio\Mapper\ArrayFieldSetter;
use Radio\Mapper\ChildCollection;
use Radio\Mapper\ChildObject;
use Radio\Mapper\Field;
use Radio\Mapper\ListMapper;
use Radio\Mapper\ObjectMapper;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;
use Radio\Mapper\MapperFactory;

class Tag extends BaseController
{

    use EntityManager;

    public function getList()
    {
        try {

            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('t')
                ->from('\Radio\Entity\Tag', 't')
                ->setMaxResults(100);
            $q = $qb->getQuery();

            $result = [];
            $tags = $q->getArrayResult();


            $m = new ListMapper();
            $m->addMapper(new Field("name"));

            $m->map($tags, $result, new ArrayFieldSetter());

            return new JsonModel($result);
        } catch (Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function get($e)
    {
        try {

            $name = $e->getRouteMatch()->getParam("name");


            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('t')
                ->from('\Radio\Entity\Tag', 't')
                ->where("t.name = :name")
                ->setMaxResults(100);
            $q = $qb->getQuery();
            $q->setParameter("name", $name);

            $tag = $q->getArrayResult()[0];

            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('e', 'x', 't')
                ->from('\Radio\Entity\Episode', 'e')
                ->join("e.text", 'x')
                ->join('x.tags', 't')
                ->where("t.name = 'tag2'");
            $q = $qb->getQuery();

            $episodes = $q->getArrayResult();


            //var_dump($episodes);
            $result = [];
            $m = new ObjectMapper();
            $m->addMapper(new Field("name"));
            $m->map($tag, $result, new ArrayFieldSetter());

            $epi = [];
            $em = new ListMapper();
            $tm = $em->addMapper(new ChildObject("text"));
            $tm->addMapper(new Field("title"));
            $em->map($episodes, $epi, new ArrayFieldSetter());

            $result['episodes'] = $epi;

            return new JsonModel($result);
        } catch (Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }


}
