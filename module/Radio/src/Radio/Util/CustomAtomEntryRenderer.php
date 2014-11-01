<?php

namespace Radio\Util;

use DateTime;
use DOMDocument;
use DOMElement;
use Zend\Feed\Uri;
use Zend\Feed\Writer;
use Zend\Feed\Writer\Renderer;
use Zend\Validator;

class CustomAtomEntryRenderer extends \Zend\Feed\Writer\Renderer\Entry\Atom {

    /**
     * Constructor
     *
     * @param  Writer\Entry $container
     */
    public function __construct(Writer\Entry $container) {
        parent::__construct($container);
    }

    /**
     * Set date entry was modified
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     * @throws Writer\Exception\InvalidArgumentException
     */
    protected function _setDateModified(DOMDocument $dom, DOMElement $root) {
        if (!$this->getDataContainer()->getDateModified()) {
            $message = 'Atom 1.0 entry elements MUST contain exactly one'
                    . ' atom:updated element but a modification date has not been set';
            $exception = new Writer\Exception\InvalidArgumentException($message);
            if (!$this->ignoreExceptions) {
                throw $exception;
            } else {
                $this->exceptions[] = $exception;
                return;
            }
        }

        $updated = $dom->createElement('updated');
        $root->appendChild($updated);
        $text = $dom->createTextNode(
                $this->getDataContainer()->getDateModified()->format(DateTime::ATOM)
        );
        $updated->appendChild($text);
    }

    /**
     * Set date entry was created
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setDateCreated(DOMDocument $dom, DOMElement $root) {
        if (!$this->getDataContainer()->getDateCreated()) {
            return;
        }
        $el = $dom->createElement('published');
        $root->appendChild($el);
        $text = $dom->createTextNode(
                $this->getDataContainer()->getDateCreated()->format(DateTime::ATOM)
        );
        $el->appendChild($text);
    }
}

?>