<?php

namespace Radio\Controller;

use Zend\I18n\Validator\DateTime;
use Zend\Mvc\Controller\AbstractActionController;
use Radio\Provider\EntityManager;
use Radio\Util\Mp3Streamer;

/**
 * Generate atom feeds.
 */
class Atom extends AbstractActionController
{

    use EntityManager;



    public function showSuperFeedAction()
    {

        $host = $this->getRequest()->getServer('HTTP_HOST');
        if ($host != null) {
            $serverRoot = "http://" . $this->getRequest()->getServer('HTTP_HOST');
        } else {
            $serverRoot = "http://tilos.hu";
        }


        $showId = $this->params()->fromRoute("id");
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s')->from('\Radio\Entity\Show', 's');
        if (is_numeric($showId)) {
            $qb->where('s.id = :id');
        } else {
            $qb->where('s.alias = :id');
        }

        $q = $qb->getQuery();
        $q->setParameter("id", $showId);
        $show = $q->getResult()[0];

        $qb->leftJoin('s.contributors', 'sa')->leftJoin('sa.author', 'a');
        $qb->leftJoin('s.urls', 'u');

        $feed = new \Zend\Feed\Writer\Feed;
        $feed->setTitle($show->getName() . ' :: Tilos Rádió');
        $feed->setId($serverRoot . '/feed/show/' . $showId);
        $feed->setFeedLink($serverRoot . '/feed/show/' . $showId, 'atom');
        $feed->setDateModified(time());

        $episodes = EpisodeUtil::getEpisodeTimes($this->getEntityManager(), \time() - 60 * 60 * 24 * 30 * 10, \time(), $show->getId());
        usort($episodes, array("\Radio\Controller\Atom", "comparator"));
        $limit = 10;
        foreach ($episodes as $episode) {
            $from = $episode['realFrom']->getTimestamp();
            //+360 => 6 minutes to include the next half hour
            $end = $episode['realTo']->getTimestamp() + 6 * 60;

            $d = getdate($from);
            $timestr = sprintf("%02d%02d", $d['hours'], $d['minutes']);

            $entry = $feed->createEntry();
            if (array_key_exists("text", $episode) && !empty($episode['text']['title'])) {
                $entry->setTitle($episode['text']['title']);
            } else {
                $entry->setTitle($episode['plannedFrom']->format("Y-m-d"));
            }

            $entry->setId(sprintf("http://tilos.hu/feed/%s/%02d/%02d/%02d/%s", $showId, $d['year'], $d['mon'], $d['mday'], $timestr));
            $entry->setLink($serverRoot . '/episode/' . $showId . "/" . date("Y/m/d", $episode['plannedFrom']->getTimestamp()));
            foreach ($show->getContributors() as $participation) {
                $entry->addAuthor(array(
                    'name' => $participation->getNick(),
                    'uri' => $serverRoot . '/author/' . $participation->getAuthor()->getId(),
                ));
            }
            $entry->setDateModified($episode['plannedTo']);
            $entry->setDateCreated($episode['plannedFrom']);
            if (array_key_exists("text", $episode) && !empty($episode['text']['content'])) {
                $entry->setDescription($episode['text']['content']);
            } else {
                $entry->setDescription("Adásnapló");
            }

            //$entry->setContent('content');
            $entry->setItunesExplicit('no');

            $duration = ($episode['plannedTo']->getTimestamp() - $episode['plannedFrom']->getTimestamp()) / 60;
            $mp3 = new Mp3Streamer();
            $origin = $mp3->getMp3Links($from, $duration + 10);
            $entry->setEnclosure(array(
                'type' => 'audio/mpeg',
                'uri' => sprintf($serverRoot . "/mp3/%d-%d.mp3", $from, $duration + 10),
                'length' => $origin->getSize()
            ));
            $feed->addEntry($entry);
            $limit--;

            if ($limit < 0) break;
        }
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->getHeaders()->addHeaders(array(
            'Content-Type' => 'text/xml; charset=utf-8'
        ));

        $renderer = new \Radio\Util\CustomAtomFeedRenderer($feed);
        $response->setContent($renderer->render()->saveXml());
        return $response;
    }

    public static function comparator($a, $b)
    {
        return ($a['plannedFrom']->getTimestamp() > $b['plannedTo']->getTimestamp()) ? -1 : +1;
    }

}

?>