<?php
require_once '../module/Radio/src/Radio/Util/Mp3Streamer.php';
use Radio\Util\Mp3Streamer;

$mp3 = new Mp3Streamer();
$mp3->combinedMp3Action();
