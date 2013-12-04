<?php

namespace Radio\Controller;

use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;

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
            foreach ($episodes as $episode) {
                if ($episode['show']) {
                    unset($episode['show']['description']);
                }
                if ($episode['m3uUrl']) {
                    $episode['m3uUrl'] = "http://" . $this->getRequest()->getServer('HTTP_HOST') . "/" . $episode['m3uUrl'];
                }

                $episode['plannedFrom'] = $episode['plannedFrom']->getTimestamp();
                $episode['plannedTo'] = $episode['plannedTo']->getTimestamp();
                $result[] = $episode;

            }
            return new JsonModel($result);
        } catch (Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function get($id) {
        try {
            $result = $this->getEntityManager()->find("\Radio\Entity\Episode", $id);
            if ($result == null) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(array("error" => "Not found"));
            } else {
                $a = $result->toArray();
                $a['shows'] = array();
                foreach ($result->getShows() as $show) {
                    $a['shows'][] = $show->getShow()->toArrayShort();
                }
                return new JsonModel($a);
            }
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function create($data) {
        try {
            // validation
            if (!isset($data['radioshow_id']) || !isset($data['plannedFrom']) ||
                !isset($data['plannedTo']) || !isset($data['realFrom']) ||
                !isset($data['realTo'])
            ) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Mandatory fields: radioshow_id, plannedFrom, plannedTo, realFrom, realTo."));
            }
            // validate show id via DB
            $show = $this->getEntityManager()->find('Radio\Entity\Show', $data['radioshow_id']);
            if (is_null($show)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Show id does not exist in DB."));
            }
            // validate textcontent id via DB
            if (isset($data['textcontent_id'])) {
                $text = $this->getEntityManager()->find('Radio\Entity\TextContent', $data['textcontent_id']);
                if (is_null($text)) {
                    $this->getResponse()->setStatusCode(400);
                    return new JsonModel(array("error" => "Textcontent id does not exist in DB."));
                }
            }
            // validate timestamps
            if (!is_numeric($data['plannedFrom']) || !is_numeric($data['plannedTo']) ||
                !is_numeric($data['realFrom']) || !is_numeric($data['plannedTo'])
            ) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Waiting dates in timestamp format (integer)."));
            }
            // convert timestamps to datetimes
            $plannedFrom = new \DateTime();
            $plannedTo = new \DateTime();
            $realFrom = new \DateTime();
            $realTo = new \DateTime();

            $plannedFrom->setTimestamp($data['plannedFrom']);
            $plannedTo->setTimestamp($data['plannedTo']);
            $realFrom->setTimestamp($data['realFrom']);
            $realTo->setTimestamp($data['realTo']);

            $episode = new \Radio\Entity\Episode();

            $episode->setShow($show);
            $episode->setPlannedFrom($plannedFrom);
            $episode->setPlannedTo($plannedTo);
            $episode->setRealFrom($realFrom);
            $episode->setRealTo($realTo);
            if (!is_null($text)) {
                $episode->setText($text);
            }

            $this->getEntityManager()->persist($episode);
            $this->getEntityManager()->flush();

            return new JsonModel(array("create" => "success"));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function update($id, $data) {
        $episode = $this->getEntityManager()->find('Radio\Entity\Episode', $id);

        // validation
        if (is_null($episode)) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array("error" => "Episode id does not exist."));
        }

        if (!isset($data['radioshow_id']) && !isset($data['plannedFrom']) &&
            !isset($data['plannedTo']) && !isset($data['realFrom']) &&
            !isset($data['realTo']) && !isset($data['textcontent_id'])
        ) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array("error" => "One of the following fields must exist: radioshow_id, plannedFrom, plannedTo, realFrom, realTo, textcontent_id."));
        }

        if (isset($data['radioshow_id'])) {
            $show = $this->getEntityManager()->find('Radio\Entity\Show', $data['radioshow_id']);
            if (is_null($show)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Show id does not exist in DB."));
            } else {
                $episode->setShow($show);
                $updated .= " Show id: " . $data['radioshow_id'];
            }
        }
        if (isset($data['plannedFrom'])) {
            if (!is_numeric($data['plannedFrom'])) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Waiting dates in timestamp format (integer)."));
            } else {
                $plannedFrom = new \DateTime();
                $plannedFrom->setTimestamp($data['plannedFrom']);
                $episode->setPlannedFrom($plannedFrom);
                $updated .= " PlannedFrom: " . $data['plannedFrom'];
            }
        }
        if (isset($data['plannedTo'])) {
            if (!is_numeric($data['plannedTo'])) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Waiting dates in timestamp format (integer)."));
            } else {
                $plannedTo = new \DateTime();
                $plannedTo->setTimestamp($data['plannedTo']);
                $episode->setPlannedTo($plannedTo);
                $updated .= " PlannedTo: " . $data['plannedTo'];
            }
        }
        if (isset($data['realFrom'])) {
            if (!is_numeric($data['realFrom'])) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Waiting dates in timestamp format (integer)."));
            } else {
                $realFrom = new \DateTime();
                $realFrom->setTimestamp($data['realFrom']);
                $episode->setRealFrom($realFrom);
                $updated .= " RealFrom: " . $data['realFrom'];
            }
        }
        if (isset($data['realTo'])) {
            if (!is_numeric($data['realTo'])) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Waiting dates in timestamp format (integer)."));
            } else {
                $realTo = new \DateTime();
                $realTo->setTimestamp($data['realTo']);
                $episode->setRealTo($realTo);
                $updated .= " RealTo: " . $data['realTo'];
            }
        }
        // validate textcontent id via DB
        if (isset($data['textcontent_id'])) {
            $text = $this->getEntityManager()->find('Radio\Entity\TextContent', $data['textcontent_id']);
            if (is_null($text)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Textcontent id does not exist in DB."));
            } else {
                $episode->setText($text);
                $updated .= " Textcontent id: " . $data['textcontent_id'];
            }
        }

        $this->getEntityManager()->flush();
        return new JsonModel(array("update" => "success", "Updated values" => $updated));
    }

    public function delete($id) {
        try {
            $episode = $this->getEntityManager()->find('Radio\Entity\Episode', $id);
            if (is_null($episode)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Episode does not exist in DB."));
            }

            $this->getEntityManager()->remove($episode);
            $this->getEntityManager()->flush();

            return new JsonModel(array("delete" => "success"));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

}
