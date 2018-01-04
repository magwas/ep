<?php

use PHPUnit\Framework\TestCase;

include_once 'class-fakewp.php';

class WPTestCase extends TestCase {

    function setData($testdata) {
        global $EP_WORLDPRESS_INTERFACE;
        $EP_WORLDPRESS_INTERFACE=new FakeWP($testdata);
        $this->WP = $EP_WORLDPRESS_INTERFACE;
    }

    function assertActionAdded($name,$value) {
        $this->assertEquals($this->WP->actions[$name], $value);
    }
}
