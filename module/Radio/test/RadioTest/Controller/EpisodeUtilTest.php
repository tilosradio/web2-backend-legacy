<?php

use RadioTest\Bootstrap;
use Radio\Controller\EpisodeUtil;

class EpisodeUtilTest extends \PHPUnit_Framework_TestCase {

    public function testGetScheduled() {
        $serviceManager = Bootstrap::getServiceManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');
        $this->assertNotNull($em);
        $result = EpisodeUtil::getScheduled($em, mktime(22, 0, 0, 1, 1, 2013), mktime(05, 59, 59, 1, 2, 2013), null);
        $this->assertEquals(4, sizeof($result));

    }

    public function testGetEpisodes1() {
        $serviceManager = Bootstrap::getServiceManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');
        $this->assertNotNull($em);
        $result = EpisodeUtil::getEpisodeTimes($em, mktime(0, 0, 0, 1, 1, 2013), mktime(23, 59, 59, 1, 31, 2013), 557);
        $this->assertEquals(4, sizeof($result));
        $this->assertEquals(mktime(8, 0, 0, 1, 4, 2013), $result[0]['plannedFrom']->getTimestamp());
        $this->assertEquals(mktime(10, 0, 0, 1, 4, 2013), $result[0]['plannedTo']->getTimestamp());
        $this->assertEquals(mktime(8, 0, 0, 1, 11, 2013), $result[1]['plannedFrom']->getTimestamp());
        $this->assertEquals(mktime(8, 0, 0, 1, 18, 2013), $result[2]['plannedFrom']->getTimestamp());
        $this->assertEquals(mktime(8, 0, 0, 1, 25, 2013), $result[3]['plannedFrom']->getTimestamp());
    }

    public function testGetEpisodes2() {
        $serviceManager = Bootstrap::getServiceManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');
        $this->assertNotNull($em);
        $result = EpisodeUtil::getEpisodeTimes($em, mktime(0, 0, 0, 10, 1, 2013), mktime(23, 59, 59, 10, 30, 2013), 557);
        $this->assertEquals(4, sizeof($result));
        $this->assertEquals(mktime(8, 0, 0, 10, 4, 2013), $result[0]['plannedFrom']->getTimestamp());
        $this->assertEquals(mktime(10, 0, 0, 10, 4, 2013), $result[0]['plannedTo']->getTimestamp());
        $this->assertEquals(mktime(8, 0, 0, 10, 11, 2013), $result[1]['plannedFrom']->getTimestamp());
        $this->assertEquals(mktime(8, 0, 0, 10, 18, 2013), $result[2]['plannedFrom']->getTimestamp());
        $this->assertEquals(mktime(8, 0, 0, 10, 25, 2013), $result[3]['plannedFrom']->getTimestamp());
    }


    public function testGetScheduled2() {
        $serviceManager = Bootstrap::getServiceManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');
        $this->assertNotNull($em);
        $result = EpisodeUtil::getScheduled($em, mktime(0, 0, 0, 1, 1, 2013), mktime(23, 59, 59, 2, 28, 2013), 485);
        $this->assertEquals(5, sizeof($result));
    }

    public function testGetEpisodeList1() {
        $serviceManager = Bootstrap::getServiceManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');
        $this->assertNotNull($em);
        $result = EpisodeUtil::getEpisodes($em, mktime(0, 0, 0, 1, 1, 2013), mktime(23, 59, 59, 2, 28, 2013), 485);
        $this->assertEquals(5, sizeof($result));
    }

    public function testGetEpisodes3() {
        $serviceManager = Bootstrap::getServiceManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');
        $this->assertNotNull($em);
        $result = EpisodeUtil::getEpisodeTimes($em, mktime(0, 0, 0, 1, 1, 2013), mktime(23, 59, 59, 2, 28, 2013), 485);
        $this->assertEquals(5, sizeof($result));
    }

    public function testGetEpisodesWithLog() {
        $serviceManager = Bootstrap::getServiceManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');
        $this->assertNotNull($em);
        $result = EpisodeUtil::getEpisodeTimes($em, mktime(0, 0, 0, 1, 1, 2013), mktime(23, 59, 59, 3, 1, 2013), 626);
        //var_dump($result);
        $this->assertEquals(5, sizeof($result));
        $this->assertContains("pályázatának", $result[4]['text']['content']);

    }


    public function testWeekStart() {
        $given = mktime(18, 22, 50, 10, 18, 2013);
        $expected = mktime(0, 0, 0, 10, 14, 2013);
        $this->assertEquals($expected, EpisodeUtil::weekStart($given));

        $given = mktime(18, 22, 50, 6, 18, 2013);
        $expected = mktime(0, 0, 0, 6, 17, 2013);
        $this->assertEquals($expected, EpisodeUtil::weekStart($given));
    }

    public function testTimeInWeek() {
        $given = mktime(0, 0, 0, 10, 14, 2013);
        $expected = mktime(10, 30, 0, 10, 16, 2013);
        $this->assertEquals($expected, EpisodeUtil::timeInWeek($given, array('weekDay' => 2, 'hourFrom' => 10, 'minFrom' => 30)));
    }

}

?>
