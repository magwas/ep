<?php // phpcs:disable Squiz.Commenting

class AcceptRules {

	function __construct(  ) {
		global $EP_WORLDPRESS_INTERFACE;
		$this->WP = & $EP_WORLDPRESS_INTERFACE;
	}
	
	public function init() {
		$this->WP->add_action( 'wp_ajax_ep_accept_rules', array( $this, 'accept_rules' ) );
	}

	function accept_rules() {
		$user = $this->WP->wp_get_current_user();
		$this->WP->update_user_meta( $user->ID, 'accepted_the_rules', 1 );
		$this->WP->echo('user=' . $user->ID);
		$this->WP->echo('accepted=' . $this->WP->get_user_meta( $user->ID, 'accepted_the_rules', true )[0]);
		$this->WP->wp_die();
	}
}


