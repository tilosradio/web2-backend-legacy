<?php
namespace RadioTest\Controller;

use RadioTest\Bootstrap;
use PHPUnit_Framework_TestCase;

class FormatterTest extends \PHPUnit_Framework_TestCase {

    protected $formatter;

    protected function setUp() {
        $this->formatter = new \Radio\Util\Formatter();
    }

    public function testLegacy() {
        // given
        $content = "<p>qwe</p>";

        // when
        $formatted = $this->formatter->format('legacy', $content);
        $this->assertEquals($content, $formatted);
    }

    public function testNormal() {
        // given
        $content = "qwe\n\nesdf";
        // when
        $formatted = $this->formatter->format('normal', $content);
        $this->assertEquals("<p>qwe</p>\n<p>esdf</p>", $formatted);
    }
}

?>