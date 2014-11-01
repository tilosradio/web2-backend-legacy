<?php
require_once '../module/Radio/src/Radio/Stream/Mp3Streamer.php';
require_once '../module/Radio/src/Radio/Stream/FileBackend.php';
require_once '../module/Radio/src/Radio/Stream/SoutBackend.php';
require_once '../module/Radio/src/Radio/Stream/Mp3Streamer.php';
require_once '../module/Radio/src/Radio/Stream/Mp3File.php';
require_once '../module/Radio/src/Radio/Stream/ResourceCollection.php';

use Radio\Stream\Mp3Streamer;

$mp3 = new \Radio\Stream\Mp3Streamer("../archive-files/online", new \Radio\Stream\FileBackend());
$mp3->combinedMp3Action();
