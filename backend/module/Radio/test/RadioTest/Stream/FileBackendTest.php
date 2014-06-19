<?php


class FileBackendTest extends \PHPUnit_Framework_TestCase
{

    public function testOutput()
    {
        $backend = new \Radio\Stream\FileBackend();
        echo "\n";
        $backend->stream(0, 10, "./module/Radio/test/testfile.txt");
        echo "\n";
        $backend->stream(9, 10, "./module/Radio/test/testfile.txt");
        echo "\n";
        $backend->stream(9, 11, "./module/Radio/test/testfile.txt");
    }


}

