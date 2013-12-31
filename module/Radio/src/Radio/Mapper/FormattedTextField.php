<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/12/13
 * Time: 8:01 PM
 */

namespace Radio\Mapper;

use Radio\Util\Formatter;

/**
 * Simple field mapping based on name.
 *
 * @package Radio\Mapper
 */
class FormattedTextField implements Mapper {

    private $name;
    private $format;
    private $formatter;

    function __construct($name, $format) {
        $this->name = $name;
        $this->format = $format;
        $this->formatter = new Formatter();
    }

    public function map(&$from, &$to) {
        if (array_key_exists($this->name, $from)) {
            $to[$this->name] = $from[$this->name];
            $to[$this->name . '_formatted'] = $this->formatter->format($this->format, $from[$this->name]);
        }
    }
}