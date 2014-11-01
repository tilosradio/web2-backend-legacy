<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;
use Radio\Mapper\MapperFactory;

/**
 * @SWG\Resource(resourcePath="/author",basePath="/api")
 */
class Author extends BaseController {

    use EntityManager;

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
        return $this->getEntityList("\Radio\Entity\Author");
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
    public function get($e) {
        $id = $this->getIdentifier($e->getRouteMatch(),$e->getRequest());

        return $this->getEntity("\Radio\Entity\Author", $id);
    }

    public function mapEntity($result) {
        $a = $result;
        $now = time();
        $episodes = EpisodeUtil::getEpisodeTimes($this->getEntityManager(), $now - 60 * 60 * 24 * 60, $now, $a['id'], true);
        $a['episodes'] = $episodes;
        $r = [];
        MapperFactory::authorMapper(['baseUrl' => $this->getServerUrl()])->map($a, $r);
        return $r;

    }

    public function mapEntityListElement($result) {
        $r = [];
        MapperFactory::authorElementMapper(['baseUrl' => $this->getServerUrl()])->map($result, $r);
        return $r;
    }

    public function findEntityObject($type, $id) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a','sa','s','u')->from('\Radio\Entity\Author', 'a');
        if (is_numeric($id)) {
            $qb->where('a.id = :id');
        } else {
            $qb->where('a.alias = :id');
        }
        $qb->leftJoin('a.contributions', 'sa')->leftJoin('sa.show', 's');
        $qb->leftJoin('a.urls', 'u');


        $q = $qb->getQuery();
        $q->setParameter("id",$id);
        return $q->getArrayResult()[0];
    }

    public function findEntityList($type) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a','c','s')->from('\Radio\Entity\Author', 'a');
        $qb->leftJoin('a.contributions', 'c')->leftJoin('c.show', 's');
        $qb->orderBy("a.name");
        $q = $qb->getQuery();
        return $q->getArrayResult();
    }



}
