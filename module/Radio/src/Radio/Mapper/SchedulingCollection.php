<?php


namespace Radio\Mapper;


use Radio\Controller\EpisodeUtil;

class SchedulingCollection implements Mapper {

    private $name;
    private $newKeyName = "schedulingText";

    function __construct($name) {
        $this->name = $name;
    }

    public function map(&$from, &$to) {
        if (array_key_exists($this->name, $from)) {
            if (!array_key_exists($this->name, $to)) {
                $to[$this->newKeyName] = [];
            }
            foreach ($from[$this->name] as &$child) {
                $to[$this->newKeyName][] = EpisodeUtil::schedulingMessage($child);
            }
        }
    }
} 