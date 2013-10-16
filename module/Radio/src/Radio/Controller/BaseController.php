<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Radio\Provider\EntityManager;
use Zend\View\Model\JsonModel;

/**
 * Base class for all of the controllers.
 */
class BaseController extends AbstractRestfulController {

    use EntityManager;

    public function getEntity($type, $id, $mapping) {
        try {
            $result = $this->getEntityManager()->find($type, $id);
            if ($result == null) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(array("error" => "Not found"));
            } else {
                $a = call_user_func($mapping, $result);
                return new JsonModel($a);
            }
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function getEntityList($type, $mapping) {
        try {
            // TODO: paging (limit/offset)
            $resultSet = $this->getEntityManager()->getRepository($type)->findAll();
            if (empty($resultSet))
                return new JsonModel(array());
            $return = array();
            foreach ($resultSet as $result) {
                $return[] = call_user_func($mapping, $result);
            }
            return new JsonModel($return);
        } catch (Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

}

?>
