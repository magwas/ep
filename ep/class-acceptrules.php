<?php // phpcs:disable Squiz.Commenting

class AcceptRules {

	public function init() {
		add_action( 'wp_ajax_ep_accept_rules', array( $this, 'accept_rules' ) );
	}

	function accept_rules() {
		$user = wp_get_current_user();
		update_user_meta( $user->ID, 'accepted_the_rules', 1 );
		echo 'user=' . $user->ID;
		echo 'accepted=' . get_user_meta( $user->ID, 'accepted_the_rules', true );
		wp_die();
	}
}


