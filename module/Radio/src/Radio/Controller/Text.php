<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;

class Text extends AbstractRestfulController {
    
    use EntityManager;      

    public function listOfTypeAction(){
        $query = $this->getEntityManager()->createQuery('SELECT t FROM \Radio\Entity\TextContent t where t.type = :type ORDER BY t.created');
        $query->setParameter("type", 'news');
        $resultSet = $query->getResult();
        if (empty($resultSet))
                return new JsonModel(array());
        $return = array();        
        foreach ($resultSet as $result) {
            $return[] = $result->toArray();
        }
        return new JsonModel($return);
    }
    public function get($id) {
        try {
            $result = $this->getEntityManager()->find("\Radio\Entity\TextContent", $id);
            if ($result == null) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(array("error" => "Not found"));
            } else {
                $a = $result->toArray();
                return new JsonModel($a);
            }
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

}
