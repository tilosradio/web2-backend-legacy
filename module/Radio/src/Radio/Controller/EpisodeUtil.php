<?php

namespace Radio\Controller;

/**
 * Utility class to return with episodes.
 *
 */
class EpisodeUtil {

    static function getEpisodeTimes($em, $show, $from, $to) {
        //$em->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
        //retrieve active rules
        $query = $em->createQuery('SELECT e FROM Radio\Entity\Scheduling e WHERE e.show = :showId ORDER BY e.weekDay,e.hourFrom,e.minFrom');
        $query->setParameter("showId", $show);
        $resultSet = $query->getArrayResult();

        $scheduled = array();
        foreach ($resultSet as $result) {
            $scheduling = $result;

            $current = new \DateTime();
            $current->setTimestamp(EpisodeUtil::weekStart($from + 60 * 60 * 5));
            $current->setTime(12, 0, 0);
            $current->modify("+" . $scheduling['weekDay'] . " days");
            while ($current->getTimestamp() < $to) {
                $real = clone $current;
                $real->setTime($scheduling['hourFrom'], $scheduling['minFrom'], 0);
                $selectedWeek = true;
                if ($scheduling['weekType'] > 1) {
                    $weekNo = ($real->getTimestamp() - $scheduling['base']->getTimestamp()) / (7 * 60 * 60 * 24);
                    if (round($weekNo) % $scheduling['weekType'] != 0) {
                        $selectedWeek = false;
                    }
                }
                if ($selectedWeek && $real->getTimestamp() >= $from && $real->getTimestamp() < $to) {
                    $e = new \Radio\Entity\Episode();
                    $realEnd = $real->getTimestamp() + $scheduling['duration'] * 60;
                    $e->setPlannedFrom($real);
                    $e->setPlannedTo(EpisodeUtil::toDateTime($realEnd));
                    $e->setM3uUrl(sprintf('m3u/%d/%d.m3u', $real->getTimestamp(), $scheduling['duration']));
                    $e->setPersistent(false);
                    $scheduled[] = $e;
                }
                $current->modify("+ 7 days");
            }
        }
        //retrieve episodes       
        $query = $em->createQuery('SELECT e FROM Radio\Entity\Episode e WHERE e.show = :showId AND e.plannedTo > :start AND e.plannedFrom < :end ORDER BY e.plannedFrom');
        $query->setParameter("showId", $show);
        $query->setParameter("start", EpisodeUtil::toDateTime($from));
        $query->setParameter("end", EpisodeUtil::toDateTime($to));
        $episodes = $query->getResult();
        foreach ($episodes as $episode) {
            $episode->setM3uUrl(sprintf('m3u/%d/%d.m3u', $episode->getPlannedFrom()->getTimestamp(),
                ($episode->getPlannedTo()->getTimestamp() - $episode->getPlannedFrom()->getTimestamp()) / 60));
        }

        $result = array();
        $si = 0; //scheduled index
        $ei = 0; //episode index;        
        while ($si < count($scheduled) || $ei < count($episodes)) {
            if ($si == count($scheduled)) {
                $result[] = $episodes[$ei];
                $ei++;
            } else if ($ei == count($episodes)) {
                $result[] = $scheduled[$si];
                $si++;
            } else {
                $sStart = $scheduled[$si]->getPlannedFrom();
                $eStart = $episodes[$ei]->getPlannedFrom();
                if ($sStart == $eStart) {
                    $result[] = $episodes[$ei];
                    $si++;
                    $ei++;
                } else if ($sStart < $eStart) {
                    $result[] = $scheduled[$si];
                    $si++;
                } else {
                    $result[] = $episodes[$ei];
                    $ei++;
                }
            }
        }
        uasort($result, array("self","comparator"));
        //merge the data
        return $result;
    }

    static function comparator($a, $b) {
        if ($a->getPlannedFrom()->getTimestamp() < $b->getPlannedFrom()->getTimestamp()) {
            return 1;
        }
        return -1;
    }

    static function toDateTime($timestamp) {
        $d = new \DateTime();
        $d->setTimestamp($timestamp);
        return $d;
    }

    static function timeInWeek($firstWeekStart, $scheduling) {
        return $firstWeekStart + $scheduling['weekDay'] * 60 * 60 * 24 + 60 * 60 * $scheduling['hourFrom'] + 60 * $scheduling['minFrom'];
    }

    static function weekStart($time) {
        return strtotime('Last Monday', $time);
    }

}

?>
