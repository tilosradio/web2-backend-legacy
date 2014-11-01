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
class TimestampField extends FieldConverter
{

    protected function convert($from)
    {
        $t = new \DateTime();
        $t->setTimestamp($from);
        return $t;
    }
}