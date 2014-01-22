<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Generate m3u files on the fly.
 */
class M3u extends AbstractActionController
{

    static public function getPrevHalfHour($time)
    {
        $processed = getdate($time);
        $min = $processed['minutes'];
        if ($min >= 30) {
            $min -= 30;
        }
        return $time - $min * 60;
    }


    public function getMp3Links($start, $duration)
    {
        $res = [];
        $from = M3u::getPrevHalfHour($start);
        $end = $from + $duration * 60;

        $curr = $from;

        for ($i = $from; $i < $end; $i += 30 * 60) {
            $d = getdate($i);
            $timestr = sprintf("%02d%02d", $d['hours'], $d['minutes']);
            $file = sprintf("http://archive.tilos.hu/online/%02d/%02d/%02d/tilosradio-%02d%02d%02d-%s.mp3", $d['year'], $d['mon'], $d['mday'], $d['year'], $d['mon'], $d['mday'], $timestr);
            $res[] = array("file" => $file, 'epoch' => $i, 'datearray' => $d);
            if ($curr % 100 < 25) {
                $curr += 30;
            } else {
                $curr += 70;
            }
        }


        return $res;
    }

    public function downloadAction()
    {
        $out = "#EXTM3U\n";
        $start = (int)$this->params()->fromRoute("from");
        $from = M3u::getPrevHalfHour($start);
        $duration = (int)$this->params()->fromRoute("duration");

        foreach ($this->getMp3Links($start, $duration) as $resource) {
            $d = $resource['datearray'];
            $timestr = sprintf("%02d%02d", $d['hours'], $d['minutes']);
            $out .= sprintf("#EXTINF:1805,Tilos %02d.%02d.%02d %s\n", $d['year'], $d['mon'], $d['mday'], $timestr);
            $out .= $resource['file'] . "\n";
        }

        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->getHeaders()->addHeaders(array(
                'Content-Type' => 'audio/x-mpegurl; charset=utf-8',
                'Content-Disposition' => sprintf("filename=\"tilos-%02d%02d%02d-%d-%d.m3u\"", $d['year'], $d['mon'], $d['mday'], $timestr, $from))
        );

        $response->setContent($out);
        return $response;
    }

    function chunked_copy($from)
    {
        # 1 meg at a time, you can adjust this.
        $buffer_size = 1048576;
        $ret = 0;
        $fin = fopen($from, "rb");
        if ($fin == false) {
            echo "--$from--";
            die();
        }
        while (!feof($fin)) {
            echo fread($fin, $buffer_size);
        }
        fclose($fin);
    }

    public function combinedMp3Action()
    {

        $start = (int)$this->params()->fromRoute("from");
        $duration = (int)$this->params()->fromRoute("duration");

        $filename = sprintf("tilos-%d-%d", $start, $duration);
        header("Content-Type: audio/mpeg");
        header("Content-Disposition: attachment; filename=\"$filename.mp3\"");


        foreach ($this->getMp3Links($start, $duration) as $resource) {
            //passthru($resource['file']);
            $this->chunked_copy($resource['file']);
        }

        die("");
    }

}

?>














