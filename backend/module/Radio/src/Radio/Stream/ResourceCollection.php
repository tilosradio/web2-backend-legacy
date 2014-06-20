<?php

namespace Radio\Stream;


class ResourceCollection
{
    public $collection = [];
    //end offset in the last file
    public $endOffset = 0;
    public $size = 0;

    public function addResource($a)
    {
        $this->collection[] = $a;
        $this->size += $a->size;
    }

    public function getSize()
    {
        $size = 0;
        foreach ($this->collection as $resource) {
            $size += $resource->calculateSize();
        }
        return $size;
    }



}
