<?php // phpcs:disable Squiz.Commenting


include_once 'ep/class-dashboard.php';
require_once 'tests/class-wptestcase.php';
require_once 'tests/class-testdata.php';


class DashboardTest extends WPTestCase {


	public function setUp() {
		parent::setUp();
        $this->dashboard=new Dashboard();
        $this->setData((new TestData())->testData);
	}

	public function testShowUnauthenticated() {
		$this->dashboard->show_dashboard();
		$this->assertEquals(
			Dashboard::DASHBOARD_HEADER .
				Dashboard::LOGIN .
				Dashboard::DASHBOARD_FOOTER,
			$this->WP->output
		);
	}

	public function testShowAuthenticatedNoAccept() {
		$this->WP->wp_set_current_user( 1 );
		$this->dashboard->show_dashboard();
		$this->assertEquals(
			Dashboard::DASHBOARD_HEADER .
				sprintf( Dashboard::ACCEPT_THE_RULES, 'Unaccepting User' ) .
				Dashboard::DASHBOARD_FOOTER,
			$this->WP->output
		);
	}
	public function testShowAcceptNoAssurance() {
		$this->WP->wp_set_current_user( 2 );
		$this->dashboard->show_dashboard();
		$this->assertEquals(
			Dashboard::DASHBOARD_HEADER .
				sprintf( Dashboard::GET_ASSURANCE, 'Accepting Uncertified User' ) .
				Dashboard::DASHBOARD_FOOTER,
			$this->WP->output
		);
	}
	public function testShowAcceptAndEmagyarAssurance() {
		$this->WP->wp_set_current_user( 3 );
		$this->dashboard->show_dashboard();
		$this->assertEquals(
			Dashboard::DASHBOARD_HEADER .
			sprintf( Dashboard::YOU_ARE_MEMBER, 'Accepting Emagyar User' ) .
			Dashboard::DASHBOARD_FOOTER,
			$this->WP->output
		);
	}
	public function testShowAcceptAndAssurance() {
		$this->WP->wp_set_current_user( 4 );
		$this->dashboard->show_dashboard();
		$this->assertEquals(
			Dashboard::DASHBOARD_HEADER .
			sprintf( Dashboard::YOU_ARE_MEMBER, 'Accepting Magyar User' ) .
			Dashboard::DASHBOARD_FOOTER,
			$this->WP->output
		);
	}
	public function testShowAcceptAndBothAssurances() {
		$this->WP->wp_set_current_user( 5 );
		$this->dashboard->show_dashboard();
		$this->assertEquals(
				Dashboard::DASHBOARD_HEADER .
				sprintf( Dashboard::YOU_ARE_MEMBER, 'Accepting Magyar and Emagyar User' ) .
				Dashboard::DASHBOARD_FOOTER,
				$this->WP->output
				);
	}
	public function test_dashboard_have_init() {
		$this->dashboard->init();
		$this->assertTrue(true);
	}
	public function test_acceptrules_shortcode() {
	    $shortcode='<div class="accept-shortcode"><button onclick="javascript:accept_rules()">Efogadom a szabályokat</button></div>';
	    $this->assertEquals($shortcode, $this->dashboard->acceptrules_shortcode());
	}

	public function test_acceptrules_shortcode_for_accepting_user() {
	    $this->WP->wp_set_current_user( 4 );
	    $shortcode='';
	    $this->assertEquals($shortcode, $this->dashboard->acceptrules_shortcode());
	}
	
	public function test_acceptrules_shortcode_is_registered() {
	    $this->dashboard->init();
	    $this->assertShortcodeAdded( 'acceptrules', array( $this->dashboard, 'acceptrules_shortcode' ) );
	}
	
	public function test_ep_css_is_enqueued() {
	    $this->dashboard->init();
	    $this->assertStyleEnqueued( 'ep-css', 'http://example.org/wp-content/plugins/assets/css/ep.css', array(), EP_VERSION, false  );
	}

	public function test_ep_js_is_enqueued() {
	    $this->dashboard->init();
	    $this->assertScriptEnqueued( 'ep-js', 'http://example.org/wp-content/plugins/assets/js/ep.js', array(), EP_VERSION, false  );
	}

}


