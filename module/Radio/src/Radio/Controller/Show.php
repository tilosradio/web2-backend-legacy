<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;

/**
 * @SWG\Resource(resourcePath="/show",basePath="/api")
 */
class Show extends BaseController {

    use EntityManager;

    public function createShortConverter() {
        $res = function($result) {
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
                };
        return $res;
    }

    public function createLongConverter() {
        $res = function($result) {
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

                    
                    $a['episodes'] = array();
                            
                    $now = time();
                    $episodes = EpisodeUtil::getEpisodeTimes($this->getEntityManager(), $a['id'], $now - 60 * 60 * 24 * 30, $now);
                    foreach ($episodes as $epi) {
                        $a['episodes'][] = $epi->toArray();
                    }
                    

                    return $a;
                };
        return $res;
    }

    /**
     * @SWG\Api(
     *   path="/show",
     *   description="Generic information about radio show",
     *   @SWG\Operation(
     *     method="GET", 
     *     summary="List all active radioshow"
     *   )
     * )
     */
    public function getList() {
        return $this->getEntityList("\Radio\Entity\Show", $this->createShortConverter());
    }

    /**
     * @SWG\Api(
     *   path="/show/{id}",
     *   @SWG\Operation(
     *     method="GET", 
     *     summary="Return information about a specific radioshow",
     *     @SWG\Parameters(
     *        @SWG\Parameter(
     *           name= "id",
     *           description="Id of the show",
     *           paramType="path",
     *           type="string"
     *        )
     *     )
     *   )
     * )
     */
    public function get($id) {
        return $this->getEntity("\Radio\Entity\Show", $id, $this->createLongConverter(true));
    }

    public function create($data) {
        // TODO: implementation
    }

    public function update($id, $data) {
        // TODO: implementation
    }

    /**
     * @SWG\Api(
     *   path="/show/{id}",
     *   @SWG\Operation(
     *     method="DELETE", 
     *     summary="Delete a radio show."
     *   )
     * )
     */
    public function delete($id) {
        // TODO: implementation
    }

}

