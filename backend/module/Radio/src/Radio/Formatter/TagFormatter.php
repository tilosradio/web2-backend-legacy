<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/30/13
 * Time: 11:07 PM
 */

namespace Radio\Formatter;


use Radio\Util\TagPattern;
use Zend\XmlRpc\Generator\DomDocument;

class TagFormatter
{


    public function format($content)
    {


        $content = preg_replace(TagPattern::$GENERIC_SIMPLE, "<a href=\"/tag/$1\"><span class=\"label label-primary\">$1</span></a>", $content);
        $content = preg_replace(TagPattern::$GENERIC_COMPLEX, "<a href=\"/tag/$1\"><span class=\"label label-primary\">$1</span></a>", $content);
        $content = preg_replace(TagPattern::$PERSON_SIMPLE, "<a href=\"/tag/$1\"><span class=\"label label-success\">$1</span></a>", $content);
        $content = preg_replace(TagPattern::$PERSON_COMPLEX, "<a href=\"/tag/$1\"><span class=\"label label-primary\">$1</span></a>", $content);

        return $content;
    }


}