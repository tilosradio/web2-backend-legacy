<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/30/13
 * Time: 11:07 PM
 */

namespace Radio\Formatter;


use Zend\XmlRpc\Generator\DomDocument;

class TagFormatter
{


    public function format($content)
    {
        return preg_replace("/#(\w+)/", "<span class=\"label label-primary\">$1</span>", $content);
    }


}