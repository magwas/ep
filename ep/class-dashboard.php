<?php declare(strict_types=1);

class Dashboard {

	const DASHBOARD_HEADER = <<<'EOT'
<div class="ep_dashboard" style="background-color:#E5E5FF;text-align: center;">
EOT;
	const DASHBOARD_FOOTER = <<<'EOT'
</div>
EOT;

	const LOGIN = <<<'EOT'
Ha szeretnél tagként résztvenni, <button id="login_button">Jelentkezz be!</button>
EOT;

	const ACCEPT_THE_RULES = <<<'EOT'
Kedves %s, ha szavazati joggal szeretnél részt venni, el kell fogadnod <a href="https://elektoriparlament.hu/alapito-okirat">a szabályainkat</a>.<br\>
EOT;
	const GET_ASSURANCE    = <<<'EOT'
Kedves %s, a regisztrálásod sikerült, már csak egy lépés van hátra: <a href="/hogyan-szerzek-magyar-vagy-emagyar-igazolast" target="blank">szerezz "magyar" vagy "emagyar" igazolást!</a>
EOT;
	const YOU_ARE_MEMBER   = <<<'EOT'
Tag vagy, %s!
EOT;

	const JOIN_BUTTON = '<button class="szakkol-join-button" onClick="javascript:ep.ajax.joinSzakkol(%s)"/>';

	function __construct() {
		global $_ep_wordpress_interface;
		$this->wp = & $_ep_wordpress_interface;
	}

	function init() {
		$this->wp->add_shortcode( 'acceptrules', array( $this, 'acceptrules_shortcode' ) );
		$this->wp->wp_enqueue_style( 'ep-css', $this->wp->plugin_dir_url( __FILE__ ) . 'assets/css/ep.css' );
		$this->wp->wp_enqueue_script( 'ep-js', $this->wp->plugin_dir_url( __FILE__ ) . 'assets/js/ep.js', array(), EP_VERSION, false );

	}
	function unauthenticated( $user ) {
		return 0 == $user->ID;
	}

	function has_assurance( $user ) {
		$assurances = $this->wp->get_user_meta( $user->ID, 'eDemoSSO_assurances' );
		if ( ! is_array( $assurances ) || ! isset( $assurances[0] ) ) {
			return;
		}
		$split_assurances = json_decode( $assurances[0] );
		return ( in_array( 'magyar', $split_assurances ) || in_array( 'emagyar', $split_assurances ) );
	}

	function did_accept( $user ) {
		$accepted = $this->wp->get_user_meta( $user->ID, 'accepted_the_rules', true );
		return $accepted;
	}

	function show_dashboard() {
		$this->wp->echo( self::DASHBOARD_HEADER );
		$this->show_dashboard_content();
		$this->wp->echo( self::DASHBOARD_FOOTER );
	}

	function show_dashboard_content() {
		$user = $this->wp->wp_get_current_user();
		if ( $this->unauthenticated( $user ) ) {
			$this->wp->echo( self::LOGIN );
			return;
		}
		$name = $user->display_name;
		if ( ! $this->did_accept( $user ) ) {
			$this->wp->echo( sprintf( self::ACCEPT_THE_RULES, $name ) );
			return;
		}
		if ( ! $this->has_assurance( $user ) ) {
			$this->wp->echo( sprintf( self::GET_ASSURANCE, $name ) );
			return;
		}
		$this->add_membership_role_if_needed( $user );
		$this->wp->echo( sprintf( self::YOU_ARE_MEMBER, $name ) );
		$this->show_join_button( $user );
	}

	private function show_join_button( $user ) {
		$post = $this->wp->get_post();
		if ( 'szakkolegium' == $post->post_type ) {
			$wrapped_meta = $this->wp->get_post_meta( $post->ID, 'members' );
			if ( isset( $wrapped_meta[0] ) ) {
				$post_meta = $wrapped_meta[0];
				if ( is_array( $post_meta ) && in_array( $user->ID, $post_meta ) ) {
					return;
				}
			}
			$this->wp->echo( sprintf( self::JOIN_BUTTON, $post->post_name ) );
		}
	}

	private function add_membership_role_if_needed( $user ) {
		if ( ! in_array( 'fullmember', $user->roles ) ) {
			$user->roles[] = 'fullmember';
		}}

	function acceptrules_shortcode() {
		$user = $this->wp->wp_get_current_user();
		if ( $this->wp->get_user_meta( $user->ID, 'accepted_the_rules' ) == [] ) {
			return '<div class="accept-shortcode"><button onclick="javascript:ep.ajax.accept_rules()">Efogadom a szabályokat</button></div>';
		}
		return '';
	}

}

