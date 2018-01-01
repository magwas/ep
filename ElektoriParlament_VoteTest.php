<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

include_once 'WPFakes.php';

include_once 'ep/ElektoriParlament_Vote.php';

class ElektoriParlament_VoteTest extends TestCase
{

	public function test_ajaxAdded() {
		$this->assertEquals("ElektoriParlament_Vote", WPFakes::$actions["wp_enqueue_scripts"][0]);
		$this->assertEquals("enqueue_scripts", WPFakes::$actions["wp_enqueue_scripts"][1]);
		$this->assertEquals("ElektoriParlament_Vote", WPFakes::$actions["wp_ajax_ep_vote_submit"][0]);
		$this->assertEquals("vote_submit", WPFakes::$actions["wp_ajax_ep_vote_submit"][1]);
	}
	public function test_shortcodeAdded() {
		$this->assertEquals("ElektoriParlament_Vote", WPFakes::$shortcodes["vote"][0]);
		$this->assertEquals("vote_shortcode", WPFakes::$shortcodes["vote"][1]);
	}

}

?>
