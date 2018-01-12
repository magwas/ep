<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
declare(strict_types=1);

require_once 'ep/class-vote.php';
require_once 'ep/class-structures.php';
require_once 'tests/class-wptestcase.php';
require_once 'tests/class-testdata.php';

class ElektoriparlamentVoteTest extends WPTestCase {


	public function setUp() {
		parent::setUp();
		$structures     = new Structures();
		$this->instance = new Vote( $structures );
		$this->setData( ( new TestData() )->test_data );
		if ( ! defined( 'EP_VERSION' ) ) {
			define( 'EP_VERSION', '0.1.1' );
		}

	}

	public function test_init() {
		$this->instance->init();
		$this->assertActionAdded( 'wp_enqueue_scripts', array( $this->instance, 'enqueue_scripts' ) );
		$this->assertShortcodeAdded( 'vote', array( $this->instance, 'vote_shortcode' ) );
		$this->assertActionAdded( 'wp_ajax_ep_vote_submit', array( $this->instance, 'vote_submit' ) );
	}

	public function test_enqueue_scripts() {
		$this->instance->enqueue_scripts();
		$this->assertScriptEnqueued( 'ep-vote', 'http://example.org/wp-content/plugins/assets/js/voteslider.js', array(), EP_VERSION, false );
	}

	public function test_shortCode() {
		$post   = $this->wp->get_post( 1 );
		$result = $this->instance->vote_shortcode();
		$rows   = '';
		foreach ( [ 2, 4, 5 ] as $postid ) {
			$rows .= sprintf( Vote::ALTERNATIVE_ROW, 'title_' . $postid, 'slug_' . $postid );
		}
		$this->assertEquals( sprintf( Vote::FORM_HTML, 1, $rows ), $result );
	}

	public function test_shortCode_in_feed() {
		$this->wp->is_feed = true;
		$result            = $this->instance->vote_shortcode();
		$this->assertEquals( 'Figyelem: A problémafelvetésben szavazás van. Látogasson el az oldalra!', $result );
	}

	public function test_vote_submit() {
		global $_POST;
		$_POST = [ 'data' => 'foo' ];
		$this->instance->vote_submit();
		$this->assertDied();
		$this->assertEquals( 'hello!foo', $this->wp->output );
	}

}


