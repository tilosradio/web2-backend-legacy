<?php
namespace Radio\Controller;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
class Program extends AbstractRestfulController {
    protected $programTable;
    public function getList() {
        return new JsonModel(array('data' => "Tűrő",));
    }
    public function get($id) {
        $result = $this->getProgramTable()->getProgram($id);
        print_r(\Zend\Json\Json::encode($result));die();
        return new JsonModel(array('data' => $result,));
    }
    public function create($data) {
    }
    public function update($id, $data) {
    }
    public function delete($id) {
    }
    public function getAlbumTable() {
    }

    public function getProgramTable()
    {
        if (!$this->programTable) {
            $sm = $this->getServiceLocator();
            $this->programTable = $sm->get('Radio\Model\ProgramTable');
        }
        return $this->programTable;
    }
}
