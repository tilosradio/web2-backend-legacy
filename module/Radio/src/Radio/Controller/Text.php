<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;

class Text extends BaseController {
    
    use EntityManager;      

    public function createLongConverter() {
        $res = function($result) {
            $a = $result;       
            return $a;
        };
        return $res;
    }
    
    public function findEntityObject($type, $id) {
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('t')->from('\Radio\Entity\TextContent', 't');
    	if (is_numeric($id)) {
    		$qb->where('t.id = :id');
    	} else {
    		$qb->where('t.alias = :id');
    	}
    
    	$q = $qb->getQuery();
    	$q->setParameter("id",$id);
    	return $q->getArrayResult()[0];
    }
    
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
    
    /**
     * @SWG\Api(
     *   path="/show/{id}",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="Returns content of a specific page",
     *     @SWG\Parameters(
     *        @SWG\Parameter(
     *           name= "id",
     *           description="Id of the text content",
     *           paramType="path",
     *           type="string"
     *        )
     *     )
     *   )
     * )
     */
    public function get($id) {
        return $this->getEntity("\Radio\Entity\TextContent", $id, $this->createLongConverter(true));
    }
    


}
