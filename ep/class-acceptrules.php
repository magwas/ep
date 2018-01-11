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
		global $EP_WORLDPRESS_INTERFACE;
		$user = $EP_WORLDPRESS_INTERFACE->wp_get_current_user();
		$EP_WORLDPRESS_INTERFACE->update_user_meta( $user->ID, 'accepted_the_rules', 1 );
		$EP_WORLDPRESS_INTERFACE->echo('user=' . $user->ID);
		$EP_WORLDPRESS_INTERFACE->echo('accepted=' . $EP_WORLDPRESS_INTERFACE->get_user_meta( $user->ID, 'accepted_the_rules', true )[0]);
		$EP_WORLDPRESS_INTERFACE->wp_die();
	}
}


