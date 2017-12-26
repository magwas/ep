<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

function add_action($name,$action) {
	global $actions;
	$actions[$name] = $action;
}

function get_user_meta($user,$key) {
	global $fixture;
	return $fixture->get_user_meta($user,$key);
}

function wp_get_current_user() {
	global $fixture;
	return $fixture->user;
}

function update_user_meta($id, $key, $value) {
	global $fixture;
	return $fixture->update_user_meta($id, $key, $value);
}
function wp_die() {
	global $fixture;
	return $fixture->wp_die();
}
include_once 'Dashboard.php';

class DashboardTest extends TestCase
{

	public function setUp() {
		global $fixture;
		$fixture = $this;
		$this->user = (object) array(
			'ID' => 1,
			'display_name' => 'Test User');
		$this->usermeta = array(
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
		);
		$this->died=false;
	}
	public function testAcceptRulesAjaxIsRegistered() {
		global $actions;
		$this->assertTrue(array_key_exists('wp_ajax_ep_accept_rules', $actions));
	}

	public function testAuthenticateduser() {
		$this->assertFalse(Dashboard::unauthenticated($this->user));
	}

	public function testUNAuthenticateduser() {
		$this->user->ID=0;
		$this->assertTrue(Dashboard::unauthenticated($this->user));
	}

	public function get_user_meta($userid, $key) {
		if (!array_key_exists($userid,$this->usermeta)) return;
		if (!array_key_exists($key,$this->usermeta[$userid])) return;
		return $this->usermeta[$userid][$key];
	}

	public function testHasAssurance() {
		$this->assertTrue(Dashboard::has_assurance($this->user));
	}

	public function testShowUnauthenticated() {
		$this->user->ID=0;
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			Dashboard::LOGIN .
			Dashboard::DASHBOARD_FOOTER);
		Dashboard::show_dashboard();
	}

	public function testShowAuthenticatedNoAccept() {
		$this->user->ID=1;
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf(Dashboard::ACCEPT_THE_RULES,'Test User') .
			Dashboard::DASHBOARD_FOOTER);
		Dashboard::show_dashboard();
	}

	public function testShowAcceptNoAssurance() {
		$this->user->ID=2;
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf(Dashboard::GET_ASSURANCE,'Test User') .
			Dashboard::DASHBOARD_FOOTER);
		Dashboard::show_dashboard();
	}
	public function testShowAcceptAndAssurance() {
		$this->user->ID=3;
		$this->expectOutputString(
			Dashboard::DASHBOARD_HEADER .
			sprintf(Dashboard::YOU_ARE_MEMBER,'Test User') .
			Dashboard::DASHBOARD_FOOTER);
		Dashboard::show_dashboard();
	}

	public function update_user_meta($id, $key, $value) {
		$this->oldmeta=$this->usermeta[$id];
		$this->usermeta[$id][$key]=$value;
	}
	public function wp_die() {
		$this->died=true;
	}
	public function testAcceptRules() {
		$this->user->ID=4;
		$this->expectOutputString("user=4accepted=1");
		Dashboard::accept_rules();
		$this->assertEquals($this->usermeta[4]['accepted_the_rules'],1);
		$this->assertEquals($this->oldmeta['accepted_the_rules'],0);
		$this->assertTrue($this->died);
	}
}

?>
