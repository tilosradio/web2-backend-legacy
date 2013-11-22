<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Radio\Provider\EntityManager;

/**
 * Generate atom feeds.
 */
class Atom extends AbstractActionController {

    use EntityManager;

    public function showFeedAction() {
        $showId = $this->params()->fromRoute("id");
        $show = $this->getEntityManager()->find("\Radio\Entity\Show", $showId);
        $feed = new \Zend\Feed\Writer\Feed;
        $feed->setTitle($show->getName() . ' :: Tilos Rádió');
        $feed->setLink('http://tilos.hu/');
        $feed->setFeedLink('http://tilos.hu/atom/' . $showId, 'atom');
        $feed->setDateModified(time());

        $episodes = EpisodeUtil::getEpisodeTimes($this->getEntityManager(), $showId, \time() - 60 * 60 * 24 * 30 * 10, \time());
        usort($episodes, array("\Radio\Controller\Atom", "comparator"));
	$limit = 30;
        foreach ($episodes as $episode) {
            $from = $episode->getPlannedFrom()->getTimestamp();
            //+360 => 6 minutes to include the next half hour
            $end = $episode->getPlannedTo()->getTimestamp() + 6 * 60;
            $idx = 1;
            for ($i = $from; $i < $end; $i += 30 * 60) {
                $d = getdate($i);
                $timestr = sprintf("%02d%02d", $d['hours'], $d['minutes']);

                $entry = $feed->createEntry();
                $entry->setTitle($show->getName() . " " . $episode->getPlannedFrom()->format("Y-m-d") . " #" . $idx);
                $entry->setId(sprintf("http://tilos.hu/feed/%s/%02d/%02d/%02d/%s", $showId, $d['year'], $d['mon'], $d['mday'], $timestr));                      
                $entry->setLink('http://tilos.hu/#/show/' . $showId);
                foreach ($show->getContributors() as $participation) {
                    $entry->addAuthor(array(
                        'name' => $participation->getNick(),
                        'uri' => 'http://tilos.hu/#/author/'.$participation->getAuthor()->getId(),
                    ));
                }
                $entry->setDateModified($episode->getPlannedTo());
                $entry->setDateCreated($episode->getPlannedFrom());
                $entry->setDescription("Az adás $idx. része");
                $entry->setContent('content');
                $entry->setItunesExplicit('no');

                $entry->setEnclosure(array(
                    'type' => 'audio/mpeg',
                    'uri' => sprintf("http://archive.tilos.hu/online/%02d/%02d/%02d/tilosradio-%02d%02d%02d-%s.mp3", $d['year'], $d['mon'], $d['mday'], $d['year'], $d['mon'], $d['mday'], $timestr),
                    'length' => '1337'
                ));
                $feed->addEntry($entry);
                $idx++;
		$limit--;
            }
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

    public static function comparator($a, $b) {
        return ($a->getPlannedFrom()->getTimestamp() > $b->getPlannedFrom()->getTimestamp()) ? -1 : +1;
    }

}

?>