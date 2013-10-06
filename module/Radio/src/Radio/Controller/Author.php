<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Author extends AbstractRestfulController {

    protected $table;

    public function getList() {
        try {            
            $resultSet = $this->getTable()->fetchAll();
            $return = [];
            foreach ($resultSet as $row) {
                $rec = (array) $row;
                unset($rec['description']);
                $return[] = $rec;                
            }            
            return new JsonModel($return);
        } catch (Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function get($id) {
        try {
            $result = $this->getTable()->getAuthor($id);
            if ($result == null) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(array("error" => "Not found"));
            } else {
                return new JsonModel(array($result));
            }
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function create($data) {
        
    }

    public function update($id, $data) {
        
    }

    public function delete($id) {
        
    }

    public function getAlbumTable() {
        
    }

    public function getTable() {
        if (!$this->table) {
            $sm = $this->getServiceLocator();
            $this->table = $sm->get('Radio\Model\AuthorTable');
        }
        return $this->table;
    }

}
