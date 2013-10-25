<?php

namespace Radio\Controller;

/**
 * Utility class to return with episodes.
 * 
 */
class EpisodeUtil {

    static function getEpisodeTimes($em, $show, $from, $to) {
        //retrieve active rules
        $query = $em->createQuery('SELECT e FROM Radio\Entity\Scheduling e WHERE e.show = :showId ORDER BY e.weekDay,e.hourFrom,e.minFrom');
        $query->setParameter("showId", $show);
        $resultSet = $query->getResult();

        $episodes = array();
        foreach ($resultSet as $result) {
            $scheduling = $result->toArray();
            $current = EpisodeUtil::weekStart($from);
            while ($current < $to) {
                $real = EpisodeUtil::timeInWeek($current, $scheduling);
                if ($real >= $from && $real < $to) {
                    $e = new \Radio\Entity\Episode();
                    $realEnd = $real + $scheduling['duration'] * 60;
                    $e->setPlannedFrom($real);
                    $e->setPlannedTo($realEnd);
                    $e->setM3uUrl(sprintf('m3u/%d/%d.m3u', $real, $scheduling['duration']));
                    $episodes[] = $e;
                }
                $current += 60 * 60 * 24 * 7;
            }
        }
        //retrieve episodes
        //TODO
        //merge the data
        return $episodes;
    }

    static function timeInWeek($firstWeekStart, $scheduling) {
        return $firstWeekStart + $scheduling['weekDay'] * 60 * 60 * 24 + 60 * 60 * $scheduling['hourFrom'] + 60 * $scheduling['minFrom'];
    }

    static function weekStart($time) {
        return strtotime('Last Monday', $time);
    }

}

?>
