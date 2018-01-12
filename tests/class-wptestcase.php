<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

include_once 'fakes/class-fakewp.php';

abstract class wpTestCase extends TestCase {

	function setData( $testdata ) {
		global $_ep_wordpress_interface;
		$_ep_wordpress_interface = new Fakewp( $testdata );
		$this->wp                = $_ep_wordpress_interface;
	}

	function assertActionAdded( $name, $value ) {
		$this->assertEquals( $this->wp->actions[ $name ], $value );
	}
	function assertFilterAdded( $name, $value ) {
		$this->assertEquals( $this->wp->filters[ $name ], $value );
	}
	function assertShortcodeAdded( $name, $value ) {
		$this->assertEquals( $this->wp->shortcodes[ $name ], $value );
	}
	function assertScriptEnqueued( $name, $path, $args, $version ) {
		$this->assertEquals( $this->wp->scripts[ $name ], [ $path, $args, $version ] );
	}
	function assertStyleEnqueued( $name, $path, $args, $version ) {
		$this->assertEquals( $this->wp->styles[ $name ], $path );
	}
	function assertDied() {
		$this->assertTrue( $this->wp->died );
	}
}
