<?php

use RadioTest\Bootstrap;
use Radio\Controller\EpisodeUtil;

class EpisodeUtilTest extends \RadioTest\Controller\TestBase {

    protected function setUp() {
        //TODO do it without any controller
        $this->initTest('Radio\Controller\Episode', new \Radio\Controller\Episode());
        $this->baseData();
    }

    public function testGetEpisodesWithStartEnd() {
        $serviceManager = Bootstrap::getServiceManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');

        $result = EpisodeUtil::getEpisodeTimes($em, mktime(0, 0, 0, 1, 1, 2013), mktime(23, 59, 59, 12, 31, 2013), 1);
        $this->assertEquals(4, sizeof($result));
        $this->assertEquals(mktime(10, 30, 0, 1, 7, 2013), $result[0]['plannedFrom']->getTimestamp());
        $this->assertEquals(mktime(12, 30, 0, 1, 7, 2013), $result[0]['plannedTo']->getTimestamp());
        $this->assertEquals(mktime(10, 30, 0, 1, 28, 2013), $result[3]['plannedFrom']->getTimestamp());
    }

    public function testGetEpisodesWithLog() {
        $serviceManager = Bootstrap::getServiceManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');
        $this->assertNotNull($em);
        $result = EpisodeUtil::getEpisodeTimes($em, mktime(0, 0, 0, 1, 1, 2013), mktime(23, 59, 59, 12, 31, 2013), 1);
        //var_dump($result);
        $this->assertEquals(4, sizeof($result));
        $this->assertContains("pályázatának", $result[3]['text']['content']);

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

    public function testSchedulingMessage() {
        $s = [];
        $s['hourFrom'] = 12;
        $s['minFrom'] = 30;
        $s['weekType'] = 1;
        $s['weekDay'] = 2;
        $this->assertEquals("minden szerda 12:30", EpisodeUtil::schedulingMessage($s));

        $s = [];
        $s['hourFrom'] = 8;
        $s['minFrom'] = 30;
        $s['weekType'] = 2;
        $s['weekDay'] = 1;
        $this->assertEquals("minden második kedd 8:30", EpisodeUtil::schedulingMessage($s));

        $s = [];
        $s['hourFrom'] = 8;
        $s['minFrom'] = 0;
        $s['weekType'] = 2;
        $s['weekDay'] = 1;
        $this->assertEquals("minden második kedd 8:00", EpisodeUtil::schedulingMessage($s));
    }

}

?>
