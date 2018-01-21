<?php //phpcs:disable WordPress.Files.FileName.InvalidClassFileName

require_once 'vendor/autoload.php';
require_once 'e2e/class-operations.php';

class UserSubscriptionTest extends Operations {

	public function test_join_szakkol() {
		$this->membership_journey();
		shell_exec( './tools/wpassure' );
		$this->join_szakkol();
		$this->assertException(
			'PHPUnit_Extensions_Selenium2TestCase_WebDriverException', 'still have join button', function() {
				$this->byXPath( "//*[@id='ep_dashboard']/button[@class='szakkol-join-button']" );
			}
		);
	}

	protected function membership_journey() {
		$this->login();
		$this->look_at_rules();
		$this->accept_rules();
	}

}
