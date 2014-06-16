<?php

namespace Radio\Util;

class Mp3File
{
    public $root;
    public $file;
    public $url;
    public $date;
    public $size = 0;
    public $epoch;

    function __construct($root, $file, $url, $epoch, $date)
    {
        $this->root = $root;
        $this->epoch = $epoch;
        $this->date = $date;
        $this->file = $this->root . $file;
        $this->url = $url;
        if (file_exists($this->file)) {
            $this->size = filesize($this->file);
        }
    }

    function checkExistence()
    {
        if (!file_exists($this->file)) {
            header('HTTP/1.0 500 Internal server error');
            die("File is missing: " . getcwd() . "/" . $this->file);
        }
    }


}

class ResourceCollection
{
    public $collection = [];
    public $startOffset = 0;
    public $size = 0;

    public function addResource($a)
    {
        $this->collection[] = $a;
        $this->size += $a->size;
    }

    public function getSize()
    {
        return $this->size - $this->startOffset;
    }

}

class Mp3Streamer
{
    public $root = "../archive-files/";

    function __construct($root)
    {
        $this->root = $root;
    }

    static public function getPrevHalfHour($time)
    {
        $processed = getdate($time);
        $min = $processed['minutes'];
        $seconds = $processed['seconds'];
        if ($min >= 30) {
            $min -= 30;
        }
        return $time - $min * 60 - $seconds;
    }


    /**
     *
     * Calculate the mp3 links based on a start time and duration.
     *
     * @param $start
     * @param $duration
     *
     */
    public function getMp3Links($start, $duration)
    {
        $result = new ResourceCollection();
        $from = Mp3Streamer::getPrevHalfHour($start);

        $end = $start + $duration * 60;
        $curr = $from;

        for ($i = $from; $i < $end; $i += 30 * 60) {
            $d = getdate($i);
            $timestr = sprintf("%02d%02d", $d['hours'], $d['minutes']);
            $filename = sprintf("/%02d/%02d/%02d/tilosradio-%02d%02d%02d-%s.mp3", $d['year'], $d['mon'], $d['mday'], $d['year'],
                $d['mon'], $d['mday'], $timestr);
            $result->addResource(new Mp3File($this->root, $filename, "http://archive.tilos.hu/online" . $filename, $i, $d));
            if ($curr % 100 < 25) {
                $curr += 30;
            } else {
                $curr += 70;
            }
        }


        $result->startOffset = (int)(($start - $from) * 38.28125 * 836);
        return $result;
    }


    function chunked_copy($from, $offset = 0)
    {
        # 1 megabyte buffer
        $buffer_size = 1048576;
        $ret = 0;
        $fin = fopen($from, "rb");
        if ($fin == false) {
            echo "--$from--";
            die("file open error");
        }
        if ($offset) {
            fseek($fin, $offset);
        }
        while (!feof($fin)) {
            echo fread($fin, $buffer_size);
        }
        fclose($fin);
    }

    function chunked_copy_sum($from, $offset)
    {
        echo "Copy file $from from $offset\n";
    }

    public function stream($from, $to, $resource)
    {
        $realStart = $from + $resource->startOffset;
        $realEnd = $realStart + $to;

        $current = 0;
        $fileIndex = 0;
        while ($current < $realEnd) {
            $currentFile = $resource->collection[$fileIndex];
            $currentFile->checkExistence();
            if ($current + $currentFile->size < $realStart) {

            } else if ($realStart < $current) {
                $this->chunked_copy($currentFile->file, 0);
            } else {
                $localOffset = $realStart - $current;
                $this->chunked_copy($currentFile->file, $localOffset);
            }
            $fileIndex++;
            $current += $currentFile->size;
            if ($fileIndex >= sizeof($resource->collection)) {
                break;
            }
        }
    }

    public function combinedMp3Action()
    {


        $uri = $_SERVER['REQUEST_URI'];
        $matches = [];
        if (preg_match('/^\/mp3\/(\d+)-(\d+).(mp3|m3u)$/', $uri, $matches, PREG_OFFSET_CAPTURE)) {
            $start = (int)$matches[1][0];
            $duration = (int)$matches[2][0];
        } else if (preg_match('/^\/mp3\/(\d+)\/(\d+)\/(\d+).*$/', $uri, $matches, PREG_OFFSET_CAPTURE)) {
            $start = mktime(
                (int)substr($matches[2][0], 0, 2),
                (int)substr($matches[2][0], 2, 2),
                (int)substr($matches[2][0], 4, 2),
                (int)substr($matches[1][0], 4, 2),
                (int)substr($matches[1][0], 6, 2),
                (int)substr($matches[1][0], 0, 4));
            $end = mktime(
                (int)substr($matches[3][0], 0, 2),
                (int)substr($matches[3][0], 2, 2),
                (int)substr($matches[3][0], 4, 2),
                (int)substr($matches[1][0], 4, 2),
                (int)substr($matches[1][0], 6, 2),
                (int)substr($matches[1][0], 0, 4));
            $duration = ($end - $start) / 60;
        } else {
            header('HTTP/1.0 500 Internal server error');
            die("Unparsable parameter");
        }

        //ok we have the parameters

        $origin = $this->getMp3Links($start, $duration);
//        print_r($origin);

        if (substr($uri, -strlen(".m3u")) === ".m3u") {
            return $this->m3uGenerator($start, $duration, $origin, $uri);
        }

        if (isset($_SERVER['HTTP_RANGE'])) {

            $ranges = array_map(
                'intval',
                explode(
                    '-',
                    substr($_SERVER['HTTP_RANGE'], 6) // Skip the `bytes=` part of the header
                )
            );

            // If the last range param is empty, it means the EOF (End of File)
            if (!$ranges[1]) {
                $ranges[1] = $origin->getSize() - 1;
            }

            // Send the appropriate headers
            header('HTTP/1.1 206 Partial Content');
            header('Accept-Ranges: bytes');
            header('Content-Length: ' . ($ranges[1] - $ranges[0])); // The size of the range

            // Send the ranges we offered
            header(
                sprintf(
                    'Content-Range: bytes %d-%d/%d', // The header format
                    $ranges[0], // The start range
                    $ranges[1], // The end range
                    $origin->getSize() // Total size of the file
                )
            );
            $this->stream($ranges[0], $ranges[1], $origin);
        } else {
            $filename = sprintf("tilos-%s-%d", date("Y-m-d-Hi", $start), $duration);
            if ($origin->getSize() < 1) {
//                header('HTTP/1.0 500 Internal server error');
                //               die("Some file is missing from the server.");
            } else {
                header("Content-Length: " . $origin->getSize());
                header("Content-Type: audio/mpeg");
                header("Content-Disposition: attachment; filename=\"$filename.mp3\"");
                header('Accept-Ranges: bytes');
            }
            $this->stream(0, $origin->getSize(), $origin);

        }


//stream the content to the browser


        die("");
    }

    private
    function m3uGenerator($start, $duration, $origin, $uri)
    {
        $filename = sprintf("tilos-%s-%d", date("Y-m-d-Hi", $start), $duration);
        header("Content-Type: audio/x-mpegurl; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename.m3u\"");
        echo "#EXTM3U\n";
        echo "#EXTINF:" . $origin->getSize() . ", Tilos Rádió - " . date("Y-m-d-Hi", $start) . "\n";
        echo "http://" . $_SERVER['SERVER_NAME'] . str_replace(".m3u", ".mp3", $uri);
        die();

    }
}

