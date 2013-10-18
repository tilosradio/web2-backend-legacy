<?php

use RadioTest\Bootstrap;
use Radio\Controller\EpisodeUtil;

class EpisodeUtilTest extends \PHPUnit_Framework_TestCase {

    public function testGetEpisodes() {
        $serviceManager = Bootstrap::getServiceManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');
        $this->assertNotNull($em);
        $result = EpisodeUtil::getEpisodeTimes($em, 557, mktime(0, 0, 0, 10, 1, 2013), mktime(23, 59, 59, 10, 30, 2013));
        $this->assertEquals(4, sizeof($result));
        $this->assertEquals(mktime(8, 0, 0, 10, 4, 2013), $result[0]);
        $this->assertEquals(mktime(8, 0, 0, 10, 11, 2013), $result[1]);
        $this->assertEquals(mktime(8, 0, 0, 10, 18, 2013), $result[2]);
        $this->assertEquals(mktime(8, 0, 0, 10, 25, 2013), $result[3]);
    }

    public function testWeekStart() {
        $given = mktime(18, 22, 50, 10, 18, 2013);
        $expected = mktime(0, 0, 0, 10, 14, 2013);
        $this->assertEquals($expected, EpisodeUtil::weekStart($given));
    }

    public function testTimeInWeek() {
        $given = mktime(0, 0, 0, 10, 14, 2013);
        $expected = mktime(10, 30, 0, 10, 16, 2013);
        $this->assertEquals($expected, EpisodeUtil::timeInWeek($given, array('weekDay' => 2, 'hourFrom' => 10, 'minFrom' => 30)));
    }

}

?>
