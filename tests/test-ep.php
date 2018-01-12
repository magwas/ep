<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
declare(strict_types=1);

define( 'ABSPATH', '/ep' );
require_once 'tests/class-testdata.php';
global $_ep_wordpress_interface;
$_ep_wordpress_interface = new Fakewp( ( new TestData() )->test_data );

include_once 'ep/ep.php';
require_once 'tests/class-wptestcase.php';

//phpcs:disable PEAR.NamingConventions.ValidClassName.StartWithCapital
class eDemo_SSOauth_Base {

}

class EPTest extends WPTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function test_setup() {
		ep_bootstrap();
		define( 'DOING_AJAX', true );
		ep_bootstrap();
		$this->assertTrue( defined( 'EP_VERSION' ) );
	}
}
