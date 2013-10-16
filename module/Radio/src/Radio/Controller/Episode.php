<?php

namespace Radio\Controller;

use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;

/**
 * @SWG\Resource(resourcePath="/episode",basePath="/api")
 */
class Episode extends BaseController {

    use EntityManager;

    public function getList() {
        try {
            $start = $this->params()->fromQuery("start", time());
            $end = $this->params()->fromQuery("end", $start + 60 * 60 * 5);
            //retrieve valid scheduling rules
            $query = $this->getEntityManager()->createQuery('SELECT e FROM Radio\Entity\Scheduling e WHERE e.weekType = :type OR e.weekType = 0 ORDER BY e.weekDay,e.hourFrom,e.minFrom');
            $query->setParameter("type", date("W", $start) % 2 + 1);
            $resultSet = $query->getResult();
            if (empty($resultSet))
                return new JsonModel(array());
            $return = array();

            $weekstart = getdate(strtotime('this week', $start));

            foreach ($resultSet as $result) {
                $a = $result->toArray();
                $epi = array();
                //$epi->setShow($result->getShow());
                $epi['show'] = $result->getShow()->toArrayShort();

                //calculate actual date from
                $from = new \DateTime();
                $from->setDate($weekstart['year'], $weekstart['mon'], $weekstart['mday']);
                $from->setTime($result->getHourFrom(), $result->getMinFrom(), 0);
                $from->add(new \DateInterval("P" . $result->getWeekDay() . "D"));

                //calculate actual date to
                $to = new \DateTime();
                $to->setDate($weekstart['year'], $weekstart['mon'], $weekstart['mday']);
                $to->setTime($result->getHourTo(), $result->getMinTo(), 0);
                $to->add(new \DateInterval("P" . $result->getWeekDay() . "D"));
                if ($result->getHourTo() == 0 && $result->getMinTo(0) == 0) {
                    $to->add(new \DateInterval("P1D"));
                }

                $epi['from'] = $from->getTimestamp();
                $epi['to'] = $to->getTimestamp();
                if($from->getTimestamp()>=$start && $to->getTimestamp()<=$end){
                    $return[] = (array) $epi;
                }
            }
            return new JsonModel($return);
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
        // TODO: implementation
    }

    public function update($id, $data) {
        // TODO: implementation
    }

    public function delete($id) {
        // TODO: implementation
    }

}
