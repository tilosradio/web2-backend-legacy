<?php


class Mp3StreamerTest extends \PHPUnit_Framework_TestCase
{

    public function testStreamer()
    {
        $backend = new \Radio\Stream\SoutBackend();
        $streamer = new TestMp3Stream(".", $backend);
        $_SERVER['REQUEST_URI'] = "/mp3/20140618/101500/112000.mp3";
        $_SERVER['SERVER_NAME'] = "localhost";
        $streamer->combinedMp3Action();

        $this->assertEquals(3, sizeof($backend->printed));

        $this->assertEquals(28802812, $backend->printed[0][1]);
        $this->assertEquals(0, $backend->printed[1][1]);
        $this->assertEquals(0, $backend->printed[2][1]);


        $this->assertEquals(57605625, $backend->printed[0][2]);
        $this->assertEquals(57605625, $backend->printed[1][2]);
        $this->assertEquals(38403750, $backend->printed[2][2]);
    }

    public function testStreamerWithRange()
    {
        $backend = new \Radio\Stream\SoutBackend();

        $streamer = new TestMp3Stream(".", $backend);
        $_SERVER['REQUEST_URI'] = "/mp3/20140618/101500/112000.mp3";
        $_SERVER['SERVER_NAME'] = "localhost";
        $_SERVER['HTTP_RANGE'] = "bytes=8-";
        $streamer->combinedMp3Action();

        $this->assertEquals(3, sizeof($backend->printed));

        $this->assertEquals(28802820, $backend->printed[0][1]);
        $this->assertEquals(0, $backend->printed[1][1]);
        $this->assertEquals(0, $backend->printed[2][1]);


        $this->assertEquals(57605625, $backend->printed[0][2]);
        $this->assertEquals(57605625, $backend->printed[1][2]);
        $this->assertEquals(38403749, $backend->printed[2][2]);
    }

    public function testStreamerWithRange2()
    {
        $backend = new \Radio\Stream\SoutBackend();

        $streamer = new TestMp3Stream(".", $backend);
        $_SERVER['REQUEST_URI'] = "/mp3/20140618/101500/112000.mp3";
        $_SERVER['SERVER_NAME'] = "localhost";
        $_SERVER['HTTP_RANGE'] = "bytes=8-10";
        $streamer->combinedMp3Action();

        $this->assertEquals(1, sizeof($backend->printed));

        $this->assertEquals(28802820, $backend->printed[0][1]);
        $this->assertEquals(28802822, $backend->printed[0][2]);

    }

    public function testStreamerWithRange3()
    {
        $backend = new \Radio\Stream\SoutBackend();

        $streamer = new TestMp3Stream(".", $backend);
        $_SERVER['REQUEST_URI'] = "/mp3/20140618/101500/112000.mp3";
        $_SERVER['SERVER_NAME'] = "localhost";
        $_SERVER['HTTP_RANGE'] = "bytes=8-28802814";
        $streamer->combinedMp3Action();

        $this->assertEquals(2, sizeof($backend->printed));

        $this->assertEquals(28802820, $backend->printed[0][1]);
        $this->assertEquals(0, $backend->printed[1][1]);


        $this->assertEquals(57605625, $backend->printed[0][2]);
        $this->assertEquals(1, $backend->printed[1][2]);
    }

    public function testStreamerWithRange4()
    {
        $backend = new \Radio\Stream\SoutBackend();

        $streamer = new TestMp3Stream(".", $backend);
        $_SERVER['REQUEST_URI'] = "/mp3/20140618/101500/112000.mp3";
        $_SERVER['SERVER_NAME'] = "localhost";
        $_SERVER['HTTP_RANGE'] = "bytes=10-100000000";
        $streamer->combinedMp3Action();

        $this->assertEquals(3, sizeof($backend->printed));

        $this->assertEquals(28802822, $backend->printed[0][1]);
        $this->assertEquals(0, $backend->printed[1][1]);


        $this->assertEquals(57605625, $backend->printed[0][2]);
        $this->assertEquals(57605625, $backend->printed[1][2]);
    }


    public function testStreamerWithRange5()
    {
        $backend = new \Radio\Stream\SoutBackend();

        $streamer = new TestMp3Stream(".", $backend);
        $_SERVER['REQUEST_URI'] = "/mp3/20140604/092959/120000.mp3";
        $_SERVER['SERVER_NAME'] = "localhost";
        $_SERVER['HTTP_RANGE'] = "bytes=0-";
        $streamer->combinedMp3Action();


    }


}

class TestMp3Stream extends \Radio\Stream\Mp3Streamer
{

    function __construct($root, $backend)
    {
        parent::__construct($root, $backend);
    }

    public function header($string)
    {
        echo "HEADER:  $string\n";
    }

}
 