<?php

namespace Radio\Controller;

use DoctrineORMModule\Proxy\__CG__\Radio\Entity\TextContent;
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
            MapperFactory::episodeElementMapper(['baseUrl' => $this->getServerUrl()])->map($episodes, $result);

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

    public function create($data) {
        try {

            $authService = $this->getServiceLocator()->get('doctrine.authenticationservice.orm_default');
            // identify the user
            $user = $authService->hasIdentity() ? $authService->getIdentity() : null;

            // validation
            if (!isset($data['radioshow_id']) || !isset($data['plannedFrom']) ||
                !isset($data['plannedTo'])
            ) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Mandatory fields: radioshow_id, plannedFrom, plannedTo."));
            }
            // validate show id via DB
            $show = $this->getEntityManager()->find('Radio\Entity\Show', $data['radioshow_id']);
            if (is_null($show)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Show id does not exist in DB."));
            }
            // validate textcontent id via DB
            if (isset($data['text']) && isset($data['text']['id'])) {
                $text = $this->getEntityManager()->find('Radio\Entity\TextContent', $data['text']['id']);
                if (is_null($text)) {
                    $this->getResponse()->setStatusCode(400);
                    return new JsonModel(array("error" => "Text id does not exist in DB."));
                }
            }
            // validate timestamps
            if (!is_numeric($data['plannedFrom']) || !is_numeric($data['plannedTo'])) {
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
            if (array_key_exists('realFrom', $data)) {
                $realFrom->setTimestamp($data['realFrom']);
            } else {
                $realFrom = $plannedFrom;
            }
            if (array_key_exists('realTo', $data)) {
                $realTo->setTimestamp($data['realTo']);
            } else {
                $realTo = $plannedTo;
            }

            $episode = new \Radio\Entity\Episode();

            $episode->setShow($show);
            $episode->setPlannedFrom($plannedFrom);
            $episode->setPlannedTo($plannedTo);
            $episode->setRealFrom($realFrom);
            $episode->setRealTo($realTo);

            if (!array_key_exists('title', $data) || !array_key_exists('content', $data)) {
                $this->getResponse()->setStatusCode(400);
                return new JsonModel(array("error" => "Mandatory fields: title, content"));
            } else {
                $t = new \Radio\Entity\TextContent();
                $t->setTitle($data['title']);
                $t->setContent($data['content']);
                $t->setType("episode");
                $t->setModified(new \DateTime());
                $t->setCreated(new \DateTime());
                $t->setFormat("html");
                $t->setAuthor($user->getUsername());
                $t->setAlias('');
                $this->getEntityManager()->persist($t);
                $episode->setText($t);

            }


            $this->getEntityManager()->persist($episode);
            $this->getEntityManager()->flush();

            return new JsonModel(array("success" => true, 'data' => array('id' => $episode->getId())));
        } catch
        (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function update($id, $data) {
        $episode = $this->getEntityManager()->find('Radio\Entity\Episode', $id);
        $updated = "";
        // validation
        if (is_null($episode)) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array("error" => "Episode does not exist."));
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
        if (isset($data['title'])) {
            $episode->getText()->setTitle($data['title']);
            $episode->getText()->setCreated(new \DateTime());
            $updated .= " title: " . $data['title'];
        }

        if (isset($data['content'])) {
            $episode->getText()->setContent($data['content']);
            $episode->getText()->setCreated(new \DateTime());
            $updated .= " content updated ";
        }


        $this->getEntityManager()->flush();
        return new JsonModel(array("success" => true, "updated" => $updated, 'data' => array('id' => $episode->getId())));
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
