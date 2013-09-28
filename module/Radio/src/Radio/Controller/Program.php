<?php
namespace Radio\Controller;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
class Program extends AbstractRestfulController {
    public function getList() {
        return new JsonModel(array('data' => "empty",));
    }
    public function get($id) {
        return new JsonModel(array('data' => "empty",));
    }
    public function create($data) {
    }
    public function update($id, $data) {
    }
    public function delete($id) {
    }
    public function getAlbumTable() {
    }
}
