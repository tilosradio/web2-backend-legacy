<?php
/**
 * Created by IntelliJ IDEA.
 * User: elek
 * Date: 12/30/13
 * Time: 11:10 PM
 */

namespace RadioTest\Controller;


use Radio\Util\RestrictedHtmlTransformer;

class RestrictedHtmlTransformerTest extends \PHPUnit_Framework_TestCase {

    public function testSimple() {
        $r = new RestrictedHtmlTransformer();
//        $this->assertEquals("<p>&Aacute;rv&iacute;zt&#369;r&#337;</p>", $r->format("<p>Árvíztűrő</p>"));
//        $this->assertEquals("<p>asasdasd</p>", $r->format("<p class=\"fekete\">asasdasd</p>"));
//        $this->assertEquals("asd", $r->format('<tab heading="qwe">asd</tab>'));
//        $this->assertEquals("<p>asasdasd</p>", $r->format("<p class=\"fekete\">asasda<script>ee</script>sd</p>"));
//        $this->assertEquals('<a href="http://asd.hu">qwe</a>', $r->format('<a href="http://asd.hu" target="blank">qwe</a>'));

        $res = $r->format('<tabset><tab heading="qwe">asd</tab><tab heading="bsd">bsd</tab></tabset>');
        //$res = $r->format(file_get_contents("/home/elek/projects/tilos/module/Radio/test/RadioTest/Util/201.html"));
        echo $res;
    }

} 