<?php

namespace Radio\Controller;


use Zend\Mvc\Controller\AbstractRestfulController;
use Radio\Provider\EntityManager;

/**
 * Base class for all of the controllers.
 */
class BaseController extends AbstractRestfulController {
    
    use EntityManager;
    
     public function getEntity($id, $type) {
        try {
            $result = $this->getEntityManager()->find($type, $id);
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

?>
