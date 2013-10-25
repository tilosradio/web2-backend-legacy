<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Generate m3u files on the fly.
 */
class M3u extends AbstractActionController {

    static public function getPrevHalfHour($time) {
       $processed = getdate($time);
       $min = $processed['minutes'];
       if ($min >= 30) {
           $min -= 30;
       }
       return $time - $min * 60;
    }
    
    public function downloadAction() {
        $out = "#EXTM3U\n";
        $from = M3u::getPrevHalfHour((int) $this->params()->fromRoute("from"));
        $duration = (int) $this->params()->fromRoute("duration");
        $end = $from + $duration * 60;

        $curr = $from;

        for ($i = $from;$i < $end;$i += 30 * 60) {
            $d = getdate($i);
            $timestr = sprintf("%02d%02d",$d['hours'],$d['minutes']);
            $out.=sprintf("#EXTINF:1805,Tilos %02d.%02d.%02d %s\n", $d['year'], $d['mon'], $d['mday'], $timestr);
            $out.=sprintf("http://archive.tilos.hu/online/%02d/%02d/%02d/tilosradio-%02d%02d%02d-%s.mp3\n",$d['year'], $d['mon'], $d['mday'], $d['year'], $d['mon'], $d['mday'],$timestr);
            if ($curr % 100 < 25) {
                $curr += 30;
            } else {
                $curr += 70;
            }
        }

        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->getHeaders()->addHeaders(array(
            'Content-Type' => 'audio/x-mpegurl; charset=utf-8',
            'Content-Disposition' => sprintf("filename=\"tilos-%02d%02d%02d-%d-%d.m3u\"", $d['year'], $d['mon'], $d['mday'], $timestr, $from, $duration))
        );

        $response->setContent($out);
        return $response;
    }

}

?>
