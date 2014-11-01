<?php

use BackendTest\Bootstrap;
use Radio\Controller\EpisodeUtil;
use Radio\Controller\M3u;

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
        $s['duration'] = 90;
        $s['weekType'] = 1;
        $s['weekDay'] = 2;
        $this->assertEquals("minden szerda 12:30-14:00", EpisodeUtil::schedulingMessage($s));

        $s = [];
        $s['hourFrom'] = 8;
        $s['minFrom'] = 30;
        $s['weekType'] = 2;
        $s['weekDay'] = 1;
        $s['duration'] = 121;
        $this->assertEquals("minden második kedd 8:30-10:31", EpisodeUtil::schedulingMessage($s));

        $s = [];
        $s['hourFrom'] = 8;
        $s['minFrom'] = 0;
        $s['weekType'] = 2;
        $s['weekDay'] = 1;
        $s['duration'] = 90;
        $this->assertEquals("minden második kedd 8:00-9:30", EpisodeUtil::schedulingMessage($s));

        $s = [];
        $s['hourFrom'] = 23;
        $s['minFrom'] = 45;
        $s['weekType'] = 2;
        $s['weekDay'] = 1;
        $s['duration'] = 30;
        $this->assertEquals("minden második kedd 23:45-0:15", EpisodeUtil::schedulingMessage($s));
    }

    public function testM3uLink(){
        $date = new \DateTime();
        $end = new \DateTime();
        $date->setTimestamp(mktime(12,30,0,10,23,2004));
        $end->setTimestamp(mktime(14,00,0,10,23,2004));
        $this->assertEquals('mp3/tilos-20041023-123000-140000.m3u',EpisodeUtil::m3uUrlLinkFromDate($date,$end));

        $date->setTimestamp(mktime(3,4,0,1,2,2004));
        $end->setTimestamp(mktime(4,35,0,1,2,2004));
        $this->assertEquals('mp3/tilos-20040102-030400-043500.m3u',EpisodeUtil::m3uUrlLinkFromDate($date,$end));
    }


    public function testgetPrevHalfHour() {
        $start = new \DateTime("2013-10-25 10:22:00");
        $res = EpisodeUtil::getPrevHalfHour($start->getTimestamp());
        $expected_end = new \DateTime("2013-10-25 10:00:00");
        $expected = $expected_end->getTimestamp();
        $this->assertEquals($expected, $res);
    }
}

?>
