<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
* Generate m3u files on the fly.
*/
class M3u extends AbstractActionController {

    public function downloadAction() {
        $out = "#EXTM3U\n";
        $year = $this->params()->fromRoute("year");
        $month = $this->params()->fromRoute("month");
	    $day = $this->params()->fromRoute("day");
	    $from = (int) $this->params()->fromRoute("from");
	    $to = (int) $this->params()->fromRoute("to");

	    $curr = $from;

        while ($curr <= $to) {
            $out.=sprintf("#EXTINF:1805,Tilos %s.%s.%s %04u\n",$year,$month,$day,$curr);
            $out.=sprintf("http://archive.tilos.hu/online/%s/%s/%s/tilosradio-%s%s%s-%04u.mp3\n",$year,$month,$day,$year,$month,$day,$curr);
            if ($curr / 100 < 25) {
                $curr += 30;
            } else {
                $curr += 70;
            }
        }

        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->getHeaders()->addHeaders(array(
            'Content-Type' => 'audio/x-mpegurl; charset=utf-8',
            'Content-Disposition' => sprintf("filename=\"tilos-%s%s%s-%04u-%04u.m3u\"",$year,$month,$day,$from,$to))
        );

        $response->setContent($out);
        return $response;
    }

}

?>
