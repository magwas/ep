<?php

class FixCommentReply {
	function __construct( $uriGenerator) {
		global $EP_WORLDPRESS_INTERFACE;
		$this->WP = & $EP_WORLDPRESS_INTERFACE;
		$this->uriGenerator = $uriGenerator;
	}
	
    function init() {
        $this->WP->add_filter('comment_reply_link', Array($this,'fix_comment_reply_link'));
    }
    function fix_comment_reply_link($link) {
        if (strpos($link, 'respond') !== false) {
                return $link;
        }
        return '<a rel="nofollow" class="comment-reply-login" href="javascript:' .
        str_replace("javascript:","",$this->uriGenerator->get_button_action('register')) .
        '">Be kell jelentkezni a válaszadáshoz</a>';
    }
}
