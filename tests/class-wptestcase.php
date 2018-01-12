<?php

use PHPUnit\Framework\TestCase;

include_once 'fakes/class-fakewp.php';

abstract class WPTestCase extends TestCase {

    function setData($testdata) {
        global $EP_WORLDPRESS_INTERFACE;
        $EP_WORLDPRESS_INTERFACE=new FakeWP($testdata);
        $this->WP = $EP_WORLDPRESS_INTERFACE;
    }

    function assertActionAdded($name,$value) {
        $this->assertEquals($this->WP->actions[$name], $value);
    }
    function assertFilterAdded($name,$value) {
        $this->assertEquals($this->WP->filters[$name], $value);
    }
    function assertShortcodeAdded($name,$value) {
        $this->assertEquals($this->WP->shortcodes[$name], $value);
    }
    function assertScriptEnqueued($name,$path, $args, $version) {
        $this->assertEquals($this->WP->scripts[$name], [$path, $args, $version]);
    }
    function assertStyleEnqueued($name,$path, $args, $version) {
        $this->assertEquals($this->WP->styles[$name], $path);
    }
    function assertDied() {
    	$this->assertTrue($this->WP->died);
    }
}
