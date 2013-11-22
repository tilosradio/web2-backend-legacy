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
                    return $result;
                };
        return $res;
    }
    
    public function findEntityObject($type, $id) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a','sa','s')->from('\Radio\Entity\Show', 's');
        if (is_numeric($id)) {
            $qb->where('s.id = :id');
        } else {
            $qb->where('s.alias = :id');
        }
        $qb->leftJoin('s.contributors', 'sa')->leftJoin('sa.author', 'a');

        $q = $qb->getQuery();
        $q->setParameter("id",$id);
        return $q->getArrayResult()[0];
    }
    
    public function createLongConverter() {
        $res = function($result) {
                    $a = $result;
                    unset($result['description']);
                    foreach ($result['contributors'] as $author) {
                        unset($author['author']['introduction']);
                    }
                    $a['schedulings'] = array();
                    $a['episodes'] = array();

                    $now = time();
                    $episodes = EpisodeUtil::getEpisodeTimes($this->getEntityManager(), $a['id'], $now - 60 * 60 * 24 * 30 * 10, $now);
                    foreach ($episodes as $epi) {
                        $a['episodes'][] = $epi->toArray();
                    }
                    
                    return $a;
                };
        return $res;
    }

    public function findEntityList($type) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s')->from('\Radio\Entity\Show', 's')->orderBy("s.name","ASC");
        $q = $qb->getQuery();
        return $q->getArrayResult();
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

