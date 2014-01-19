<?php

namespace RadioAdmin\Controller;

use Radio\Controller\BaseController;
use Radio\Mapper\ArrayFieldSetter;
use Radio\Mapper\Field;
use Radio\Mapper\MapperFactory;
use Radio\Mapper\ObjectFieldSetter;
use Radio\Mapper\ObjectMapper;
use Zend\Db\Sql\Expression;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;


class Show extends BaseController
{

    use EntityManager;


    public function update($e)
    {

        $id = $this->params()->fromRoute("id");
        $data = $this->getRawData($e);

        $show = $this->getEntityManager()->find("\Radio\Entity\Show", $id);

        $mapper = new ObjectMapper(new ObjectFieldSetter());
        $mapper->addMapper(new Field("name"));
        $mapper->addMapper(new Field("description"));

        $mapper->map($data, $show);

        $this->getEntityManager()->persist($show);
        $this->getEntityManager()->flush();
        return new JsonModel(array("success" => true));

    }

}

