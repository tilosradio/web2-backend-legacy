<?php

namespace Radio\Controller;

/**
 * Utility class to return with episodes.
 *
 */
class EpisodeUtil
{

    static function getScheduled($em, $from, $to, $show)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('e', 's', 'c', 'a')->from('\Radio\Entity\Scheduling', 'e');
        $qb->leftJoin("e.show", "s");
        $qb->leftJoin('s.contributors', 'c');
        $qb->leftJoin('c.author', 'a');

        if ($show != null) {
            $qb->where('e.show = :showId');
        } else {
            // $qb->where('s.status = 1');
        }
        $qb->orderBy("e.weekDay,e.hourFrom,e.minFrom");
        $query = $qb->getQuery();
        if ($show != null) {
            $query->setParameter("showId", $show);
        }
        $resultSet = $query->getArrayResult();
        $scheduled = [];
        $now = new \DateTime();
        foreach ($resultSet as $result) {
            $scheduling = $result;

            $current = new \DateTime();
            $current->setTimestamp(EpisodeUtil::weekStart($from + 60 * 60 * (5 - 7 * 24)));
            $current->setTime(12, 0, 0);
            $current->modify("+" . $scheduling['weekDay'] . " days");
            while ($to) {
                $real = clone $current;
                $real->setTime($scheduling['hourFrom'], $scheduling['minFrom'], 0);
                if ($real->getTimestamp() > $to) {
                    break;
                }
                $selectedWeek = true;
                if ($scheduling['weekType'] > 1) {
                    $weekNo = ($real->getTimestamp() - $scheduling['base']->getTimestamp()) / (7 * 60 * 60 * 24);
                    if (floor($weekNo) % $scheduling['weekType'] != 0) {
                        $selectedWeek = false;
                    }
                }

                if ($selectedWeek && $real->getTimestamp() >= $from && $real->getTimestamp() < $to && $real->getTimestamp() < $scheduling['validTo']->getTimestamp() && $real->getTimestamp() >= $scheduling['validFrom']->getTimestamp()) {
                    $e = [];
                    $realEnd = $real->getTimestamp() + $scheduling['duration'] * 60;
                    $e['plannedFrom'] = $real;
                    $e['plannedTo'] = EpisodeUtil::toDateTime($realEnd);
                    $e['realFrom'] = $e['plannedFrom'];
                    $e['realTo'] = new \DateTime();
                    $e['realTo']->setTimestamp($e['plannedTo']->getTimestamp() + 60 * 15);
                    if ($now->getTimestamp() > $realEnd) {
                        $e['m3uUrl'] = EpisodeUtil::m3uUrlLinkFromDate($real, $e['realTo']);
                    }
                    $e['persistent'] = false;
                    $e['show'] = $scheduling['show'];
                    $scheduled[] = $e;
                }
                $current->modify("+ 7 days");
            }
        }
        return $scheduled;
    }

    static function getEpisodes($em, $from, $to, $show = null)
    {
        $current = new \DateTime();
        $now = new \DateTime();

        $qb = $em->createQueryBuilder();
        $qb->select('e', 's', 'c', 't', 'a')->from('\Radio\Entity\Episode', 'e');
        if ($show != null) {
            $qb->where("e.show = :showId AND e.plannedTo > :start AND e.plannedFrom < :end AND s.status = 1");
        } else {
            $qb->where("e.plannedTo > :start AND e.plannedFrom < :end AND s.status = 1");
        }
        $qb->leftJoin('e.show', 's');
        $qb->leftJoin('s.contributors', 'c');
        $qb->leftJoin('e.text', 't');
        $qb->leftJoin('c.author', 'a');


        $qb->orderBy("e.plannedFrom");
        $query = $qb->getQuery();

        if ($show != null) {
            $query->setParameter("showId", $show);
        }
        $query->setParameter("start", EpisodeUtil::toDateTime($from));
        $query->setParameter("end", EpisodeUtil::toDateTime($to));
        $episodes = $query->getArrayResult();
        foreach ($episodes as &$episode) {
            $episode['persistent'] = true;
            if ($now->getTimestamp() > $episode['plannedTo']->getTimestamp()) {
                $episode['m3uUrl'] = EpisodeUtil::m3uUrlLink($episode);
            }
        }
        return $episodes;
    }

    static function m3uUrlLinkFromDate($date, $end)
    {
        $d = getdate($date->getTimestamp());
        $from = sprintf('%02d%02d%02d', $d['hours'], $d['minutes'], $d['seconds']);
        $endd = getdate($end->getTimestamp());
        $to = sprintf('%02d%02d%02d', $endd['hours'], $endd['minutes'], $endd['seconds']);

        return sprintf('mp3/tilos-%02d%02d%02d-%s-%s.m3u', $d['year'], $d['mon'], $d['mday'],
            $from, $to);
    }

    static function m3uUrlLink($episode)
    {
        $to = new \DateTime();;
        if ($episode['realTo'] == $episode['plannedTo']) {
            $to->setTimestamp($episode['realTo']->getTimestamp() + 60 * 15);
        } else {
            $to = $episode['realTo'];
        }
        return EpisodeUtil::m3uUrlLinkFromDate($episode['realFrom'], $to);
    }

    static function merge($episodes, $scheduled)
    {
        $result = [];
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
                $sStart = $scheduled[$si]['plannedFrom'];
                $eStart = $episodes[$ei]['plannedFrom'];
                if ($sStart->getTimestamp() == $eStart->getTimestamp()) {
                    $result[] = $episodes[$ei];
                    $si++;
                    $ei++;
                } else if ($sStart->getTimestamp() < $eStart->getTimestamp()) {
                    $result[] = $scheduled[$si];
                    $si++;
                } else {
                    $result[] = $episodes[$ei];
                    $ei++;
                }
            }
        }
        return $result;
    }

    static function getEpisodeTimes($em, $from, $to, $show = null, $reverse = false)
    {
        //$em->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
        //retrieve active rules
        $scheduled = EpisodeUtil::getScheduled($em, $from, $to, $show);
        //retrieve episodes
        $episodes = EpisodeUtil::getEpisodes($em, $from, $to, $show);

        $result = EpisodeUtil::merge($episodes, $scheduled);
        if ($reverse) {
            usort($result, array("self", "reverseComparator"));
        } else {
            usort($result, array("self", "comparator"));

        }
        return $result;
    }

    static function reverseComparator($a, $b)
    {
        if ($a['plannedFrom']->getTimestamp() > $b['plannedFrom']->getTimestamp()) {
            return -1;
        }
        return 1;
    }

    static function comparator($a, $b)
    {
        if ($a['plannedFrom']->getTimestamp() > $b['plannedFrom']->getTimestamp()) {
            return 1;
        }
        return -1;
    }

    static function toDateTime($timestamp)
    {
        $d = new \DateTime();
        $d->setTimestamp($timestamp);
        return $d;
    }

    static function timeInWeek($firstWeekStart, $scheduling)
    {
        return $firstWeekStart + $scheduling['weekDay'] * 60 * 60 * 24 + 60 * 60 * $scheduling['hourFrom'] + 60 * $scheduling['minFrom'];
    }

    static function weekStart($time)
    {
        return strtotime('Last Monday', $time);
    }

    static function schedulingMessage($scheduling)
    {
        $str = "minden ";
        if ($scheduling['weekType'] == 2) {
            $str .= "második ";
        }
        $days = ["hétfő", "kedd", "szerda", "csütörtök", "péntek", "szombat", "vasárnap"];
        $str .= $days[$scheduling['weekDay']];
        $to = $scheduling['hourFrom'] * 60 + $scheduling['minFrom'] + $scheduling['duration'];
        $toMin = $to % 60;
        $toHour = ($to - $toMin) / 60;
        if ($toHour >= 24) {
            $toHour -= 24;
        }
        $str .= " " . sprintf("%d:%02d-%d:%02d", $scheduling['hourFrom'], $scheduling['minFrom'], $toHour, $toMin);
        return $str;
    }

    static function getMp3StreamLinks($start, $duration)
    {
        $res = [];
        $from = EpisodeUtil::getPrevHalfHour($start);
        $end = $from + $duration * 60;

        $curr = $from;

        for ($i = $from; $i < $end; $i += 30 * 60) {
            $d = getdate($i);
            $timestr = sprintf("%02d%02d", $d['hours'], $d['minutes']);
            $filename = sprintf("/%02d/%02d/%02d/tilosradio-%02d%02d%02d-%s.mp3", $d['year'], $d['mon'], $d['mday'], $d['year'],
                $d['mon'], $d['mday'], $timestr);
            $res[] = array("filename" => $filename, "file" => "http://archive.tilos.hu/online" . $filename, 'epoch' => $i,
                'datearray' => $d);
            if ($curr % 100 < 25) {
                $curr += 30;
            } else {
                $curr += 70;
            }
        }
        return $res;
    }

    static function getPrevHalfHour($time)
    {
        $processed = getdate($time);
        $min = $processed['minutes'];
        if ($min >= 30) {
            $min -= 30;
        }
        return $time - $min * 60;
    }

}

?>
