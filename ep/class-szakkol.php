<?php declare(strict_types=1);

class Szakkol {
	const SLUG_REGEX = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

	function __construct() {
		global $_ep_wordpress_interface;
		$this->wp = & $_ep_wordpress_interface;
	}

	function init() {
		$this->wp->add_action( 'wp_ajax_ep_join_szakkol', array( $this, 'join_szakkol' ) );
	}

	function join_szakkol() {
		$user      = $this->wp->wp_get_current_user();
		$post_data = $this->wp->get_post_data();
		if ( ! isset( $post_data['szakkol'] ) ) {
			$this->return_bad_request();
		}
		$szakkol = $post_data['szakkol'];
		if ( ! preg_match( self::SLUG_REGEX, $szakkol ) ) {
			$this->return_bad_request();
		}
		$args  = array(
			'name'        => $szakkol,
			'post_type'   => 'szakkolegium',
			'post_status' => 'publish',
			'numberposts' => 1,
		);
		$posts = $this->wp->get_posts( $args );
		if ( ! isset( $posts[0] ) ) {
			$this->return_bad_request();
		}
		$post         = $posts[0];
		$wrapped_meta = $this->wp->get_post_meta( 8, 'members' );
		if ( isset( $wrapped_meta[0] ) ) {
			$members = $wrapped_meta[0];
		} else {
			$members = [];
		}
		$members[] = $user->ID;
		$this->wp->update_post_meta( $post->ID, 'members', $members );
		$this->wp->status_header( 200 );
		$this->wp->wp_die();
	}

	private function return_bad_request() {
		$this->wp->status_header( 400 );
		$this->wp->wp_die();
	}

}
