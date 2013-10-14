<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;

class Author extends AbstractRestfulController {
    
    use EntityManager;
  
    public function getList() {
        try {
            // TODO: paging (limit/offset)
            $resultSet = $this->getEntityManager()->getRepository("\Radio\Entity\Author")->findAll();
            if (empty($resultSet))
                return new JsonModel(array());
            $return = array();
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

    public function create($data)
    {
        // TODO: implementation
    }
    
    public function update($id, $data)
    {
        // TODO: implementation
    }
    
    public function delete($id)
    {
        // TODO: implementation
    }
}
