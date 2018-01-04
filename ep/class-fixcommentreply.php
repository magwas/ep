<?php

class FixCommentReply {
    function init() {
        add_filter('comment_reply_link', Array($this,'fix_comment_reply_link'));
    }
    function fix_comment_reply_link($link) {
        if (strpos($link, 'respond') !== false) {
                return $link;
        }
        $me= new eDemo_SSOauth_Base();
        return '<a rel="nofollow" class="comment-reply-login" href="javascript:' .
        str_replace("javascript:","",$me->get_button_action('register')) .
        '">Be kell jelentkezni a válaszadáshoz</a>';
    }
}
