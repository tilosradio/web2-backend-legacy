<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/12/13
 * Time: 8:01 PM
 */

namespace Radio\Mapper;

/**
 * Date field mapping based on name.
 *
 * @package Radio\Mapper
 */
class DateField implements Mapper {

    private $name;

    function __construct($name) {
        $this->name = $name;
    }

    public function map(&$from, &$to) {
        $to[$this->name] = $from[$this->name]->getTimestamp();
    }
}