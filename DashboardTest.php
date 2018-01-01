<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require 'WPFakes.php';

include_once 'ep/Dashboard.php';

class DashboardTest extends TestCase
{

	public function setUp() {
		WPFakes::$user = (object) array(
			'ID' => 1,
			'display_name' => 'Test User');
		WPFakes::$usermeta = array(
			1 => array(
				'eDemoSSO_assurances'=> array(0 => '["magyar"]')
			),
			2 => array(
				'accepted_the_rules'=> 1
			),
			3 => array(
				'accepted_the_rules'=> 1,
				'eDemoSSO_assurances'=> array(0 => '["magyar"]')
			),
			4 => array(
				'accepted_the_rules'=> 0
			),
			5 => array(
				'accepted_the_rules'=> 1,
				'eDemoSSO_assurances'=> array(0 => '["emagyar"]')
			),
		);
	}
	public function testAcceptRulesAjaxIsRegistered() {
		$this->assertTrue(array_key_exists('wp_ajax_ep_accept_rules', WPFakes::$actions));
	}

	public function testShowUnauthenticated() {
		WPFakes::$user->ID=0;
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			Dashboard::LOGIN .
			Dashboard::DASHBOARD_FOOTER);
		Dashboard::show_dashboard();
	}

	public function testShowAuthenticatedNoAccept() {
		WPFakes::$user->ID=1;
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf(Dashboard::ACCEPT_THE_RULES,'Test User') .
			Dashboard::DASHBOARD_FOOTER);
		Dashboard::show_dashboard();
	}

	public function testShowAcceptNoAssurance() {
		WPFakes::$user->ID=2;
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf(Dashboard::GET_ASSURANCE,'Test User') .
			Dashboard::DASHBOARD_FOOTER);
		Dashboard::show_dashboard();
	}
	public function testShowAcceptAndEmagyarAssurance() {
		WPFakes::$user->ID=5;
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf(Dashboard::YOU_ARE_MEMBER,'Test User') .
			Dashboard::DASHBOARD_FOOTER);
		Dashboard::show_dashboard();
	}
	public function testShowAcceptAndAssurance() {
		WPFakes::$user->ID=3;
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf(Dashboard::YOU_ARE_MEMBER,'Test User') .
			Dashboard::DASHBOARD_FOOTER);
		Dashboard::show_dashboard();
	}

	public function testAcceptRules() {
		WPFakes::$user->ID=4;
		$this->expectOutputString("user=4accepted=1");
		Dashboard::accept_rules();
		$this->assertEquals(WPFakes::$usermeta[4]['accepted_the_rules'],1);
		$this->assertEquals(WPFakes::$oldmeta['accepted_the_rules'],0);
		$this->assertTrue(WPFakes::$died);
	}
}

?>
