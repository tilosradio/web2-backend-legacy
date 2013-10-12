<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;

class Show extends AbstractRestfulController {

    protected $em;

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function convertData($result) {
        $a = $result->toArray();
        foreach ($result->getAuthors() as $author) {
            //$a = ShowAuthor record, $a->getAuthor = author record
            $tmp = $author->getAuthor()->toArrayShort();
            $tmp['nick'] = $author->getNick();
            $a['authors'][] = $tmp;
        }
        $a['schedulings'] = array();
        foreach ($result->getSchedulings() as $scheduling) {
            $a['schedulings'][] = $scheduling->toArray();
        }
        return $a;
    }

    public function getList() {
        try {
            $resultSet = $this->getEntityManager()->getRepository("\Radio\Entity\Show")->findAll();
            $return = array();
            foreach ($resultSet as $result) {
                $return[] = $this->converData($result);
            }
            return new JsonModel($return);
        } catch (Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function get($id) {
        //try {
        $result = $this->getEntityManager()->find("\Radio\Entity\Show", $id);
        if ($result == null) {
            $this->getResponse()->setStatusCode(404);
            return new JsonModel(array("error" => "Not found"));
        } else {            
            return new JsonModel($this->convertData($result));
        }
        /* } catch (\Exception $ex) {
          $this->getResponse()->setStatusCode(500);
          return new JsonModel(array("error" => $ex->getMessage()));
          } */
    }

}

