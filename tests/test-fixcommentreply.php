<?php declare(strict_types=1);


include_once 'ep/class-fixcommentreply.php';
require_once 'tests/class-wptestcase.php';
require_once 'tests/class-testdata.php';


class FixCommentReplyTest extends wpTestCase {

	public function setUp() {
		parent::setUp();
		$this->instance = new FixCommentReply( new FakeUriGenerator() );
		$this->setData( ( new TestData() )->testData );
	}

	public function testAcceptRulesAjaxIsRegistered() {
		$this->instance->init();
		$this->assertFilterAdded( 'comment_reply_link', array( $this->instance, 'fix_comment_reply_link' ) );
	}

	public function testLinkContainingRespondIsNotFixed() {
		$this->assertEquals(
			$this->instance->fix_comment_reply_link( '<a href="https://example.com/comment/?asldkjrespondalskdjl"' ),
			'<a href="https://example.com/comment/?asldkjrespondalskdjl"'
		);
	}
	public function testOtherLinksGotReplaced() {
		$this->assertEquals(
			$this->instance->fix_comment_reply_link( '<a href="https://example.com/comment/?asldkjskdjl"' ),
			'<a rel="nofollow" class="comment-reply-login" href="javascript:' .
				'registerUri(blabla)' .
				'">Be kell jelentkezni a válaszadáshoz</a>'
		);
	}
}


