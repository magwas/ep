<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
declare(strict_types=1);

require_once 'class-testbase.php';
require_once 'tests/fakes/class-fakewp.php';
require_once 'tests/class-testdata.php';

class FakewpTest extends TestBase {

	function setUp() {
		if ( ! isset( $this->wp ) ) {
			$this->wp = new Fakewp( ( new TestData() )->test_data );
			self::initialize();
			$post                    = $this->wp->get_post( $this->post_id );
			$post->data['thumbnail'] = '/tmp/foo.jpg';
		}
	}

	public function test_echo() {
		$this->wp->echo( 'hello' );
		$this->assertEquals( 'hello', $this->wp->output );
	}

}
