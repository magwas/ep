<?php declare(strict_types=1);

class FixCommentReply {
	function __construct( $uri_generator ) {
		global $_ep_wordpress_interface;
		$this->wp            = & $_ep_wordpress_interface;
		$this->uri_generator = $uri_generator;
	}

	function init() {
		$this->wp->add_filter( 'comment_reply_link', array( $this, 'fix_comment_reply_link' ) );
	}
	function fix_comment_reply_link( $link ) {
		if ( strpos( $link, 'respond' ) !== false ) {
				return $link;
		}
		return '<a rel="nofollow" class="comment-reply-login" href="javascript:' .
		str_replace( 'javascript:', '', $this->uri_generator->get_button_action( 'register' ) ) .
		'">Be kell jelentkezni a válaszadáshoz</a>';
	}
}
