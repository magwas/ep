<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
declare(strict_types = 1);

include_once 'ep/class-dashboard.php';
require_once 'tests/class-wptestcase.php';
require_once 'tests/class-testdata.php';

class DashboardTest extends WPTestCase {


	public function setUp() {
		parent::setUp();
		$this->dashboard = new Dashboard();
		$this->setData( ( new TestData() )->test_data );
	}

	public function testShowUnauthenticated() {
		$this->dashboard->show_dashboard();
		$this->assertEquals( Dashboard::DASHBOARD_HEADER . Dashboard::LOGIN . Dashboard::DASHBOARD_FOOTER, $this->wp->output );
	}

	public function testShowAuthenticatedNoAccept() {
		$this->wp->wp_set_current_user( 1 );
		$this->dashboard->show_dashboard();
		$this->assertEquals( Dashboard::DASHBOARD_HEADER . sprintf( Dashboard::ACCEPT_THE_RULES, 'Unaccepting User' ) . Dashboard::DASHBOARD_FOOTER, $this->wp->output );
	}

	public function testShowAcceptNoAssurance() {
		$this->wp->wp_set_current_user( 2 );
		$this->dashboard->show_dashboard();
		$this->assertEquals( Dashboard::DASHBOARD_HEADER . sprintf( Dashboard::GET_ASSURANCE, 'Accepting Uncertified User' ) . Dashboard::DASHBOARD_FOOTER, $this->wp->output );
	}

	public function testShowAcceptAndEmagyarAssurance() {
		$this->wp->wp_set_current_user( 3 );
		$this->dashboard->show_dashboard();
		$this->assertEquals( Dashboard::DASHBOARD_HEADER . sprintf( Dashboard::YOU_ARE_MEMBER, 'Accepting Emagyar User' ) . Dashboard::DASHBOARD_FOOTER, $this->wp->output );
	}

	public function testShowAcceptAndAssurance() {
		$this->wp->wp_set_current_user( 4 );
		$this->dashboard->show_dashboard();
		$this->assertEquals( Dashboard::DASHBOARD_HEADER . sprintf( Dashboard::YOU_ARE_MEMBER, 'Accepting Magyar User' ) . Dashboard::DASHBOARD_FOOTER, $this->wp->output );
	}

	public function testShowAcceptAndBothAssurances() {
		$this->wp->wp_set_current_user( 5 );
		$this->dashboard->show_dashboard();
		$this->assertEquals( Dashboard::DASHBOARD_HEADER . sprintf( Dashboard::YOU_ARE_MEMBER, 'Accepting Magyar and Emagyar User' ) . Dashboard::DASHBOARD_FOOTER, $this->wp->output );
	}

	public function test_dashboard_have_init() {
		$this->dashboard->init();
		$this->assertTrue( true );
	}

	public function test_acceptrules_shortcode() {
		$this->assertEquals( Dashboard::ACCEPTRULES_SHORTCODE, $this->dashboard->acceptrules_shortcode() );
	}

	public function test_acceptrules_shortcode_for_accepting_user() {
		$this->wp->wp_set_current_user( 4 );
		$shortcode = '';
		$this->assertEquals( $shortcode, $this->dashboard->acceptrules_shortcode() );
	}

	public function test_acceptrules_shortcode_is_registered() {
		$this->dashboard->init();
		$this->assertShortcodeAdded(
			'acceptrules', array(
				$this->dashboard,
				'acceptrules_shortcode',
			)
		);
	}

	public function test_ep_css_is_enqueued() {
		$this->dashboard->init();
		$this->assertStyleEnqueued( 'ep-css', 'http://example.org/wp-content/plugins/assets/css/ep.css', array(), EP_VERSION, false );
	}

	public function test_ep_js_is_enqueued() {
		$this->dashboard->init();
		$this->assertScriptEnqueued( 'ep-js', 'http://example.org/wp-content/plugins/assets/js/ep.js', [ 'jquery' ], EP_VERSION, false );
	}
}


