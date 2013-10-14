<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;

/**
 * @SWG\Resource(resourcePath="/show")
 */
class Show extends AbstractRestfulController {
    
    use EntityManager;
    
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

    /**
     *      @SWG\Api(path="/show",
     *          description="Get all of the active radio shows.",
     *              @SWG\Operation(
     *                  method="GET", 
     *                  summary="List all active radioshow"
     *      )
     * )
     * 
     */
    public function getList() {
        try {
            $resultSet = $this->getEntityManager()->getRepository("\Radio\Entity\Show")->findAll();
            if (empty($resultSet))
                return new JsonModel(array());
            $return = array();
            foreach ($resultSet as $result) {
                $return[] = $this->convertData($result);
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

