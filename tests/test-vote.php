<?php // phpcs:disable Squiz.Commenting
//declare(strict_types=1);


require_once 'ep/class-vote.php';
require_once 'tests/class-wptestcase.php';

class ElektoriparlamentVoteTest extends WPTestCase {


	public function setUp() {
		parent::setUp();
	}

	public function test_init() {
        $this->instance->init();
        $this->assertActionAdded( 'wp_enqueue_scripts', array( $this->vote, 'enqueue_scripts' ) );
        $this->assertShortcodeAdded( 'vote', array( $this->vote, 'vote_shortcode' ) );
        $this->assertActionAdded( 'wp_ajax_ep_vote_submit', array( $this->vote, 'vote_submit' ) );
	}

    public function test_shortCode() {
        $result = $this->vote->vote_shortcode();
        $this->assertEquals(sprintf(Vote::FORM_HTML,1, sprintf(Vote::ALTERNATIVE_ROW, "title 1", "top")),$result);
    }

}


