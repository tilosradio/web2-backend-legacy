<?php

namespace Radio\Controller;

use DoctrineORMModule\Proxy\__CG__\Radio\Entity\TextContent;
use Radio\Mapper\ArrayFieldSetter;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;
use Radio\Mapper\MapperFactory;


/**
 * @SWG\Resource(resourcePath="/episode",basePath="/api")
 */
class Episode extends BaseController {

    use EntityManager;

    /**
     * @SWG\Api(
     *   path="/episode",
     *   description="List of the exact episodes for a specific time range",
     * @SWG\Operation(
     *     method="GET"
     *   )
     * )
     */
    public function getList() {
        try {
            $start = $this->params()->fromQuery("start", time());
            $end = $this->params()->fromQuery("end", $start + 60 * 60 * 5);
            $episodes = EpisodeUtil::getEpisodeTimes($this->getEntityManager(), $start, $end);
            $result = [];
            MapperFactory::episodeElementMapper(['baseUrl' => $this->getServerUrl()])->map($episodes, $result, new ArrayFieldSetter());

            return new JsonModel($result);
        } catch (Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function get($e) {
        try {
            $id = $this->getIdentifier($e->getRouteMatch(),$e->getRequest());

            $result = $this->getEntityManager()->find("\Radio\Entity\Episode", $id);
            if ($result == null) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(array("error" => "Not found"));
            } else {
                $a = $result->toArray();
                $a['shows'] = array();
//                foreach ($result->getShows() as $show) {
//                    $a['shows'][] = $show->getShow()->toArrayShort();
//                }
                return new JsonModel($a);
            }
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }



}
