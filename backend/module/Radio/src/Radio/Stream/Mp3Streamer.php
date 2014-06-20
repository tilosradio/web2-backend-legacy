<?php

namespace Radio\Stream;


class Mp3Streamer
{
    public $root = "../archive-files/";

    public $backend;

    function __construct($root, $backend)
    {
        $this->root = $root;
        $this->backend = $backend;
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
     * @param $start unix timestamp in sec
     * @param $duration duration in min
     *
     */
    public function getMp3Links($start, $duration)
    {
        $result = new ResourceCollection();
        $from = Mp3Streamer::getPrevHalfHour($start);

        $end = $start + $duration * 60;
        $i = $from;
        for (; $i < $end; $i += 30 * 60) {
            $d = getdate($i);
            $timestr = sprintf("%02d%02d", $d['hours'], $d['minutes']);
            $filename = sprintf("/%02d/%02d/%02d/tilosradio-%02d%02d%02d-%s.mp3", $d['year'], $d['mon'], $d['mday'], $d['year'],
                $d['mon'], $d['mday'], $timestr);
            $result->addResource(new Mp3File($this->root, $this->backend, $filename, "http://archive.tilos.hu/online" . $filename, $i, $d));
        }

        $result->collection[0]->startMin = (int)($start - $from);
        $result->collection[sizeof($result->collection) - 1]->endMin = (int)($end - ($i - 30 * 60));
        return $result;
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
            $this->header('HTTP/1.0 500 Internal server error');
            die("Unparsable parameter");
        }

        //ok we have the parameters

        $origin = $this->getMp3Links($start, $duration);

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
            $this->header('HTTP/1.1 206 Partial Content');
            $this->header('Accept-Ranges: bytes');
            $this->header('Content-Length: ' . ($ranges[1] - $ranges[0])); // The size of the range

            // Send the ranges we offered
            $this->header(
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
                $this->header("Content-Length: " . $origin->getSize());
                $this->header("Content-Type: audio/mpeg");
                $this->header("Content-Disposition: attachment; filename=\"$filename.mp3\"");
                $this->header('Accept-Ranges: bytes');
            }
            $this->stream(0, $origin->getSize(), $origin);

        }
    }

    private function m3uGenerator($start, $duration, $origin, $uri)
    {
        $filename = sprintf("tilos-%s-%d", date("Y-m-d-Hi", $start), $duration);
        $this->header("Content-Type: audio/x-mpegurl; charset=utf-8");
        $this->header("Content-Disposition: attachment; filename=\"$filename.m3u\"");
        echo "#EXTM3U\n";
        echo "#EXTINF:" . $origin->getSize() . ", Tilos Rádió - " . date("Y-m-d-Hi", $start) . "\n";
        echo "http://" . $_SERVER['SERVER_NAME'] . str_replace(".m3u", ".mp3", $uri);
        die();

    }

    public function header($string)
    {
        header($string);
    }

    private function stream($from, $to, $resource)
    {
        //index from the beginning of the first file.
        $current = 0;

        foreach ($resource->collection as $file) {
            $partSize = $file->calculateSize();
            $nextCurrent = $partSize + $current;

            $startOffset = $file->startOffset();
            $endOffset = $file->endOffset();

            if ($nextCurrent < $from || $current > $to) {
                //to early
                $current = $nextCurrent;
                continue;
            }
            if ($nextCurrent > $to) {
                $endOffset = $startOffset + $to - $current;
            }
            if ($current < $from) {
                $startOffset += $from - $current;

            }


            $this->backend->stream($startOffset, $endOffset, $file);

            $current = $nextCurrent;
        }

    }
}

