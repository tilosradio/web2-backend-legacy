<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/30/13
 * Time: 11:07 PM
 */

namespace Radio\Util;


use Zend\XmlRpc\Generator\DomDocument;

class RestrictedHtmlTransformer {

    protected $allowedTags = [];

    function __construct() {
        libxml_use_internal_errors(true);
        $this->allowedTags['p'] = [];
        $this->allowedTags['html'] = [];
        $this->allowedTags['body'] = [];
        $this->allowedTags['b'] = [];
        $this->allowedTags['br'] = [];
        $this->allowedTags['ul'] = [];
        $this->allowedTags['nl'] = [];
        $this->allowedTags['i'] = [];
        $this->allowedTags['em'] = [];

        $this->allowedTags['li'] = [];

        $this->allowedTags['h1'] = [];
        $this->allowedTags['h2'] = [];
        $this->allowedTags['h3'] = [];
        $this->allowedTags['strong'] = [];
        $this->allowedTags['img'] = ['src'];
        $this->allowedTags['a'] = ['href'];
    }

    public function format($content) {
        $html = new \DomDocument('1.0', 'UTF-8');
        $html->substituteEntities = false;
        $html->preserveWhiteSpace = true;
        $html->formatOutput = false;
        $html->loadHTML('<?xml encoding="UTF-8">' . $content);
        $this->parseElement($html);

        return trim(preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(array('<?xml encoding="UTF-8">', '<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $html->saveHTML())));

    }

    protected function parseElement($dom) {

        $node = $dom;
        if ($node->nodeType == XML_ELEMENT_NODE) {
            if ($node->hasAttributes()) {
                $toRemove = [];
                foreach ($node->attributes as $attr) {
                    if (!array_key_exists($node->nodeName, $this->allowedTags) || !in_array($attr->name, $this->allowedTags[$node->nodeName])) {
                        $toRemove[] = $attr->name;
                    }

                }
                foreach ($toRemove as $attrName) {
                    $node->removeAttribute($attrName);
                }
            }

        }
        if ($dom->hasChildNodes()) {
            $toVisit = [];
            foreach ($dom->childNodes as $node) {
                $toVisit[] = $node;
            }
            foreach ($toVisit as $node) {
                $this->parseElement($node);
            }
        }
        if (($dom->nodeType == XML_ELEMENT_NODE || $dom->nodeType == XML_CDATA_SECTION_NODE) && !array_key_exists($dom->nodeName, $this->allowedTags)) {

            $parent = $dom->parentNode;

            if ($parent) {
                $toAdd = [];
                if ($dom->hasChildNodes()) {
                    foreach ($dom->childNodes as $node) {
                        $toAdd[] = $node;
                    }
                    foreach ($toAdd as $node) {
                        $parent->insertBefore($node, $dom);
                    }
                    $parent->removeChild($dom);
                }


            }
        }

    }

} 