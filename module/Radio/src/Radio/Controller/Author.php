<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Author extends AbstractRestfulController {

    protected $em;

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function getList() {
        try {
            $resultSet = $this->getEntityManager()->getRepository("\Radio\Entity\Author")->findAll();
            $return = [];
            foreach ($resultSet as $result) {
                $a = $result->toArray();
                $a['shows'] = array();
                foreach ($result->getShows() as $show) {
                    $a['shows'][] = $show->getShow()->toArrayShort();
                }
                
                $return[] = $a;
            }
            return new JsonModel($return);
        } catch (Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function get($id) {
        try {
            $result = $this->getEntityManager()->find("\Radio\Entity\Author", $id);
            if ($result == null) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(array("error" => "Not found"));
            } else {
                $a = $result->toArray();
                $a['shows'] = array();
                foreach ($result->getShows() as $show) {
                    $a['shows'][] = $show->getShow()->toArrayShort();
                }
                return new JsonModel($a);
            }
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

}
