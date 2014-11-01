<?php

namespace Radio\Mapper;


interface Mapper {

    public function map(&$from, &$to, $setter);
} 