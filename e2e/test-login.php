<?php //phpcs:disable WordPress.Files.FileName.InvalidClassFileName

require_once 'vendor/autoload.php';

class UserSubscriptionTest extends PHPUnit_Extensions_Selenium2TestCase {

	public function setUp() {
		$this->setHost( 'localhost' );
		$this->setPort( 4444 );
		$this->setBrowserUrl( 'http://localhost/' );
		$this->setBrowser( 'firefox' );
	}

	public function tearDown() {
		$this->stop();
	}

	public function testFormSubmissionWithUsername() {
		$this->login();
		$this->look_at_rules();
		$this->waitForPageReload(
			function () {
				$this->byXPath( "//*[@id='ep_acceptrules']" )->click();
			}, 10000
		);
		$this->dashboard_link = $this->byXPath( "//*[@id='ep_dashboard']/a" );
		$this->assertEquals( 'http://localhost/hogyan-szerzek-magyar-vagy-emagyar-igazolast', $this->dashboard_link->attribute( 'href' ) );
	}
	private function look_at_rules() {
		$this->url( 'http://localhost/' );
		$this->dashboard_link = $this->byXPath( "//*[@id='ep_dashboard']/a" );
		$this->assertEquals( 'http://localhost/alapito-okirat', $this->dashboard_link->attribute( 'href' ) );
		$this->waitForPageReload(
			function () {
				$this->dashboard_link->click();
			}, 10000
		);
	}


	private function login() {
		$this->url( 'http://localhost/wp-login.php' );
		$this->byId( 'user_login' )->value( 'bob' );
		$this->byId( 'user_pass' )->value( 'bobpassword' );
		$this->waitForPageReload(
			function () {
				$this->byId( 'loginform' )->submit();
			}, 10000
		);
		$this->assertEquals( 'Howdy, bob', $this->byId( 'wp-admin-bar-my-account' )->text() );
	}

	function waitForPageReload( $navigate_f, $timeout ) {
		$id = $this->byCssSelector( 'html' )->getId();
		call_user_func( $navigate_f );
		$this->waitUntil(
			function () use ( $id ) {
				$html = $this->byCssSelector( 'html' );
				if ( $html->getId() != $id ) {
					return true;
				}
			}, $timeout
		);
	}
	private function wait_user() {
		fwrite( STDERR, "\n\nWaiting for you\n\n" );
		$handle = fopen( 'php://stdin', 'r' );
		$line   = fgets( $handle );
		fclose( $handle );
	}
}
