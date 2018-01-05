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

	public function ShowUnauthenticated() {
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			Dashboard::LOGIN .
			Dashboard::DASHBOARD_FOOTER
		);
		$this->dashboard->show_dashboard();
	}

	public function ShowAuthenticatedNoAccept() {
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf( Dashboard::ACCEPT_THE_RULES, wp_get_current_user()->display_name ) .
			Dashboard::DASHBOARD_FOOTER
		);
		$this->dashboard->show_dashboard();
	}
	public function ShowAcceptNoAssurance() {
		$this->user->update_meta('accepted_the_rules', 1 );
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf( Dashboard::GET_ASSURANCE, wp_get_current_user()->display_name ) .
			Dashboard::DASHBOARD_FOOTER
		);
		$this->dashboard->show_dashboard();
	}
	public function ShowAcceptAndEmagyarAssurance() {
		$this->user->update_meta('accepted_the_rules', 1 );
		$this->user->update_meta( $user, 'eDemoSSO_assurances', '["emagyar"]' );
		wp_set_current_user( $user );
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf( Dashboard::YOU_ARE_MEMBER, wp_get_current_user()->display_name ) .
			Dashboard::DASHBOARD_FOOTER
		);
		$this->dashboard->show_dashboard();
	}
	public function ShowAcceptAndAssurance() {
		$this->user->update_meta( $user, 'accepted_the_rules', 1 );
		$this->user->update_meta( $user, 'eDemoSSO_assurances', '["magyar"]' );
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf( Dashboard::YOU_ARE_MEMBER, wp_get_current_user()->display_name ) .
			Dashboard::DASHBOARD_FOOTER
		);
		$this->dashboard->show_dashboard();
	}
	public function AcceptRules() {
		$this->user->update_meta( $user );
		$this->assertEquals( array(), get_user_meta( $user, 'accepted_the_rules' ) );
		$this->expectOutputString( 'user=' . $user . 'accepted=1' );
		$this->setExpectedException( 'WPDieException' );
		$this->dashboard->accept_rules();
		$this->assertEquals( 1, get_user_meta( $user, 'accepted_the_rules' ) );
	}

	public function AcceptRulesAjaxIsRegistered() {
		global $wp_filter;
		$this->assertTrue( isset( $wp_filter['wp_ajax_ep_accept_rules'] ) );
	}

}


