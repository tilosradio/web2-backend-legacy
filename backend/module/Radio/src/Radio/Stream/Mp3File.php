<?php

namespace Radio\Stream;

class Mp3File
{
    public $root;
    public $file;
    public $url;
    public $date;
    public $size;
    public $epoch;
    public $startMin = 0;
    public $endMin;
    public $ratio;

    function __construct($root, $backend, $file, $url, $epoch, $date)
    {
        $this->root = $root;
        $this->epoch = $epoch;
        $this->date = $date;
        $this->file = $this->root . $file;
        $this->url = $url;
        //$this->size = $backend->getSize($file);
        $this->endMin = 1800;
        $this->ratio = 38.28125 * 836;
        //$this->ratio = 32000;

    }

    function checkExistence()
    {
        return file_exists($this->file);
    }

    function calculateSize()
    {
        return $this->endOffset() - $this->startOffset();
    }

    function startOffset(){
        return (int) ($this->startMin * $this->ratio);
    }
    function endOffset(){
        return (int) ($this->endMin * $this->ratio);
    }


}

?>