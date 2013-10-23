<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;

/**
 * @SWG\Resource(resourcePath="/author",basePath="/api")
 */
class Author extends BaseController {

    use EntityManager;

    public function createConverter() {
        return function($result) {
                    $a = $result->toArray();
                    $a['shows'] = array();
                    foreach ($result->getShows() as $show) {
                        $a['shows'][] = $show->getShow()->toArrayShort();
                    }
                    return $a;
                };
    }

    /**
     * @SWG\Api(
     *   path="/author",
     *   description="Function related to the authors of the radio shows",
     *   @SWG\Operation(
     *     method="GET", 
     *     summary="List all active author"
     *   )
     * )
     */
    public function getList() {
        return $this->getEntityList("\Radio\Entity\Author", $this->createConverter());
    }

    /**
     * @SWG\Api(
     *   path="/author/{id}",
     *   @SWG\Operation(
     *     method="GET", 
     *     summary="Return information about a specific author",
     *     @SWG\Parameters(
     *        @SWG\Parameter(
     *           name= "id",
     *           description="Id of the author",
     *           paramType="path",
     *           type="string"
     *        )
     *     )
     *   )
     * )
     */
    public function get($id) {
        return $this->getEntity("\Radio\Entity\Author", $id, $this->createConverter());
    }

    public function create($data) {
        // TODO: implementation
    }

    public function update($id, $data) {
        // TODO: implementation
    }

    public function delete($id) {
        // TODO: implementation
    }

}
