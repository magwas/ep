<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
declare(strict_types=1);

include_once 'ep/class-dashboard.php';
require_once 'tests/class-wptestcase.php';
require_once 'tests/class-testdata.php';


class MemberRoletest extends WPTestCase {

	public function setUp() {
		parent::setUp();
		$this->dashboard = new Dashboard();
		$this->setData( ( new TestData() )->test_data );
	}

	public function testAccepting_and_assured_user_have_fullmember_role_after_dashboard_run() {
		$this->wp->wp_set_current_user( 5 );
		$this->dashboard->show_dashboard();
		$user = $this->wp->wp_get_current_user();
		$this->assertTrue( in_array( 'fullmember', $user->roles ) );
	}

}
