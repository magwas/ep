<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class DashboardTest extends WP_UnitTestCase {


	public function setUp() {
		parent::setUp();
	}

	public function testShowUnauthenticated() {
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			Dashboard::LOGIN .
			Dashboard::DASHBOARD_FOOTER
		);
		Dashboard::show_dashboard();
	}

	public function testShowAuthenticatedNoAccept() {
		$user = $this->factory->user->create();
		wp_set_current_user( $user );
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf( Dashboard::ACCEPT_THE_RULES, wp_get_current_user()->display_name ) .
			Dashboard::DASHBOARD_FOOTER
		);
		Dashboard::show_dashboard();
	}
	public function testShowAcceptNoAssurance() {
		$user = $this->factory->user->create();
		update_user_meta( $user, 'accepted_the_rules', 1 );
		wp_set_current_user( $user );
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf( Dashboard::GET_ASSURANCE, wp_get_current_user()->display_name ) .
			Dashboard::DASHBOARD_FOOTER
		);
		Dashboard::show_dashboard();
	}
	public function testShowAcceptAndEmagyarAssurance() {
		$user = $this->factory->user->create();
		update_user_meta( $user, 'accepted_the_rules', 1 );
		update_user_meta( $user, 'eDemoSSO_assurances', '["emagyar"]' );
		wp_set_current_user( $user );
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf( Dashboard::YOU_ARE_MEMBER, wp_get_current_user()->display_name ) .
			Dashboard::DASHBOARD_FOOTER
		);
		Dashboard::show_dashboard();
	}
	public function testShowAcceptAndAssurance() {
		$user = $this->factory->user->create();
		update_user_meta( $user, 'accepted_the_rules', 1 );
		update_user_meta( $user, 'eDemoSSO_assurances', '["magyar"]' );
		wp_set_current_user( $user );
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf( Dashboard::YOU_ARE_MEMBER, wp_get_current_user()->display_name ) .
			Dashboard::DASHBOARD_FOOTER
		);
		Dashboard::show_dashboard();
	}
	public function testAcceptRules() {
		$user = $this->factory->user->create();
		wp_set_current_user( $user );
		$this->assertEquals( array(), get_user_meta( $user, 'accepted_the_rules' ) );
		$this->expectOutputString( 'user=' . $user . 'accepted=1' );
		$this->setExpectedException( 'WPDieException' );
		Dashboard::accept_rules();
		$this->assertEquals( 1, get_user_meta( $user, 'accepted_the_rules' ) );
	}

	public function testAcceptRulesAjaxIsRegistered() {
		global $wp_filter;
		$this->assertTrue( isset( $wp_filter['wp_ajax_ep_accept_rules'] ) );
	}

}


