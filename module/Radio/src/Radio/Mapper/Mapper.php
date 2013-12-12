<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/12/13
 * Time: 7:56 PM
 */

namespace Radio\Mapper;


interface Mapper {

    public function map(&$from, &$to);
} 