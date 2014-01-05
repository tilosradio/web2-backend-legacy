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
class DateField extends FieldConverter
{

    protected function convert($from)
    {
        return $from->getTimestamp();
    }
}