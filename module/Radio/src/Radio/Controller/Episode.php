<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class Episode extends BaseController {

    public function getList() {
        try {

            $query = $this->getEntityManager()->createQuery('SELECT e FROM Radio\Entity\Scheduling e WHERE e.weekType = :type OR e.weekType = 0 ORDER BY e.weekDay,e.hourFrom,e.minFrom');
            $query->setParameter("type",date("W")%2 + 1);
            $resultSet = $query->getResult();

            $return = [];

            $weekstart = getdate(strtotime('this week', time()));



            foreach ($resultSet as $result) {
                $a = $result->toArray();
                $epi = [];
                //$epi->setShow($result->getShow());
                $epi['show'] = $result->getShow()->toArrayShort();

                $from = new \DateTime();
                $from->setDate($weekstart['year'], $weekstart['mon'], $weekstart['mday']);
                $from->setTime($result->getHourFrom(), $result->getMinFrom(), 0);
                $from->add(new \DateInterval("P" . $result->getWeekDay() . "D"));
                
                $to = new \DateTime();
                $to->setDate($weekstart['year'], $weekstart['mon'], $weekstart['mday']);
                $to->setTime($result->getHourTo(), $result->getMinTo(), 0);
                $to->add(new \DateInterval("P" . $result->getWeekDay() . "D"));

                $epi['from'] = $from->format('Y-m-d H:i:s');
                $epi['to'] = $to->format('Y-m-d H:i:s');
                $return[] = (array) $epi;
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

}
