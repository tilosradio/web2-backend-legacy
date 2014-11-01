<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 6/18/14
 * Time: 10:14 PM
 */

namespace Radio\Stream;


class SoutBackend
{

    public $printed = [];

    public function checkExistence($currentFile)
    {
        return true;
    }

    public function stream($from, $to, $resource)
    {
        $this->printed[] = [$resource, $from, $to];;
        echo "Streaming $resource->file from $from to $to\n";
    }

    public function getSize($file)
    {
        return 10000000;
    }

}