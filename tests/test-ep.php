<?php

define('ABSPATH','/ep');
require_once 'tests/class-testdata.php';
global $EP_WORLDPRESS_INTERFACE;
$EP_WORLDPRESS_INTERFACE=new FakeWP((new TestData())->testData);

include_once 'ep/ep.php';
require_once 'tests/class-wptestcase.php';

class eDemo_SSOauth_Base {
	
}

class EPTest extends WPTestCase {

	public function setUp() {
		parent::setUp();
	}
	
	public function test_setup() {
		epBootstrap();
		define('DOING_AJAX', true);
		epBootstrap();
		$this->assertTrue(defined('EP_VERSION'));
	}
}
