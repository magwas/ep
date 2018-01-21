<?php //phpcs:disable WordPress.Files.FileName.InvalidClassFileName

require_once 'vendor/autoload.php';

class E2eTest extends PHPUnit_Extensions_Selenium2TestCase {

	public function setUp() {
		$this->setHost( 'localhost' );
		$this->setPort( 4444 );
		$this->setBrowserUrl( 'http://localhost/' );
		$this->setBrowser( 'firefox' );
	}

	public function tearDown() {
		$this->stop();
	}

	protected function assertException( $type, $message, callable $function ) {
		$exception = null;

		try {
			call_user_func( $function );
		} catch ( Exception $e ) {
			$exception = $e;
		}
		self::assertEquals( $type, get_class( $exception ), $message );
	}

	protected function waitForPageReload( $navigate_f, $timeout ) {
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
		$this->waitUntil(
			function () {
				$ready_state = $this->execute(
					[
						'script' => 'return document.readyState',
						'args'   => [],
					]
				);
				return 'complete' == $ready_state;
			}, $timeout
		);
	}
	protected function wait_user() {
		fwrite( STDERR, "\n\nWaiting for you\n\n" );
		$handle = fopen( 'php://stdin', 'r' );
		$line   = fgets( $handle );
		fclose( $handle );
	}
}
