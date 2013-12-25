<?php

namespace Radio\Controller;

use Radio\Mapper\MapperFactory;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;

/**
 * @SWG\Resource(resourcePath="/show",basePath="/api")
 */
class Show extends BaseController {

    use EntityManager;

    public function findEntityObject($type, $id) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a', 'sa', 's', 'u')->from('\Radio\Entity\Show', 's');
        if (is_numeric($id)) {
            $qb->where('s.id = :id');
        } else {
            $qb->where('s.alias = :id');
        }
        $qb->leftJoin('s.contributors', 'sa')->leftJoin('sa.author', 'a');
        $qb->leftJoin('s.urls', 'u');


        $q = $qb->getQuery();
        $q->setParameter("id", $id);
        return $q->getArrayResult()[0];
    }

    public function mapEntity($result) {
        $a = $result;
        $now = time();
        $episodes = EpisodeUtil::getEpisodeTimes($this->getEntityManager(), $now - 60 * 60 * 24 * 60, $now, $a['id'], true);
        $a['episodes'] = $episodes;
        $r = [];
        MapperFactory::showMapper(['baseUrl' => $this->getServerUrl()])->map($a, $r);
        return $r;

    }

    public function mapEntityListElement($result) {
        $r = [];
        MapperFactory::showElementMapper(['baseUrl' => $this->getServerUrl()])->map($result, $r);
        return $r;
    }

    public function findEntityList($type) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s')->from('\Radio\Entity\Show', 's')->where("s.status = 1")->orderBy("s.name", "ASC");
        $q = $qb->getQuery();
        return $q->getArrayResult();
    }

    /**
     * @SWG\Api(
     *   path="/show",
     *   description="Generic information about radio show",
     * @SWG\Operation(
     *     method="GET",
     *     summary="List all active radioshow"
     *   )
     * )
     */
    public function getList() {
        return $this->getEntityList("\Radio\Entity\Show");
    }

    /**
     * @SWG\Api(
     *   path="/show/{id}",
     * @SWG\Operation(
     *     method="GET",
     *     summary="Return information about a specific radioshow",
     * @SWG\Parameters(
     * @SWG\Parameter(
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
        return $this->getEntity("\Radio\Entity\Show", $id);
    }

    public function listOfEpisodesAction() {
        $id = $this->params()->fromRoute("id");
        $start = $this->params()->fromQuery("from", time() - 60 * 24 * 60 * 60);
        $end = $this->params()->fromQuery("to", time());
        $episodes = EpisodeUtil::getEpisodeTimes($this->getEntityManager(), $start, $end, $id, true);

        $result = [];
        $mapper = MapperFactory::shortEpisodeElementMapper(['baseUrl' => $this->getServerUrl()]);
        $mapper->map($episodes,$result);

        return new JsonModel($result);
    }



    /**
     * @SWG\Api(
     *   path="/show/{id}",
     * @SWG\Operation(
     *     method="DELETE",
     *     summary="Delete a radio show."
     *   )
     * )
     */
    public function delete($id) {
        // TODO: implementation
    }

}

