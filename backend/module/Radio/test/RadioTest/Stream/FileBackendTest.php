<?php


use Radio\Stream\Mp3File;

class FileBackendTest extends \PHPUnit_Framework_TestCase
{

    public function testOutput()
    {

        $backend = new \Radio\Stream\FileBackend();
        $file = new Mp3File("module/.", $backend, "./module/Radio/test/testfile.txt", "", "", '"');
        echo "\n";
        $backend->stream(0, 10, $file);
        echo "\n";
        $backend->stream(9, 10, $file);
        echo "\n";
        $backend->stream(9, 11, $file);
    }


}

