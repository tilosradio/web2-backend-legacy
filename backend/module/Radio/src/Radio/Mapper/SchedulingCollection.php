<?php


namespace Radio\Mapper;


use Radio\Controller\EpisodeUtil;

class SchedulingCollection implements Mapper
{

    private $name;
    private $newKeyName = "schedulingText";

    function __construct($name)
    {
        $this->name = $name;
    }

    public function map(&$from, &$to, $setter)
    {
        if (array_key_exists($this->name, $from)) {
            $res = [];
            foreach ($from[$this->name] as &$child) {
                $res[] = EpisodeUtil::schedulingMessage($child);

            }
            $setter->set($to, $this->newKeyName, $res);
        }
    }
} 