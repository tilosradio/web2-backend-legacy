<?php

namespace Radio\Util;


class TagPattern
{

    public static $GENERIC_SIMPLE = "/(?<![&#])#([\w&;áíűőüöúóéÁÍŰŐÜÖÚÓÉ-]+)/";

    public static $GENERIC_COMPLEX = "/\#\{(.+?)\}/";

    public static $PERSON_SIMPLE = "/(?<![\w@])\@([\w&;áíűőüöúóéÁÍŰŐÜÖÚÓÉ-]+)/";

    public static $PERSON_COMPLEX = "/(?<![\w@])\@\{(.+?)\}/";
} 