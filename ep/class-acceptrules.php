<?php declare(strict_types=1);

class AcceptRules {

	function __construct() {
		global $_ep_wordpress_interface;
		$this->wp = & $_ep_wordpress_interface;
	}

	public function init() {
		$this->wp->add_action( 'wp_ajax_ep_accept_rules', array( $this, 'accept_rules' ) );
	}

	function accept_rules() {
		global $_ep_wordpress_interface;
		$user = $_ep_wordpress_interface->wp_get_current_user();
		$_ep_wordpress_interface->update_user_meta( $user->ID, 'accepted_the_rules', 1 );
		$_ep_wordpress_interface->echo( 'user=' . $user->ID );
		$_ep_wordpress_interface->echo( 'accepted=' . $_ep_wordpress_interface->get_user_meta( $user->ID, 'accepted_the_rules', true )[0] );
		$_ep_wordpress_interface->wp_die();
	}
}


