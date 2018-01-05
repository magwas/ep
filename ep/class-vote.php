<?php // phpcs:disable Squiz.Commenting

class Vote {
	const FORM_HTML = <<<'EOT'
	<form id="%1$s">
	<script>
	VoteSlider('%1$s', {
	  %2$s
	});
	</script>
EOT;
	// id=<post id>
	const ALTERNATIVE_ROW = <<<'EOT'
		'%2$s' : { 'name': '%1$s (<a href="/%2$s" target="_blank">...</a>)'},
EOT;
	// label="egyik sem", slug="C2"
	function __construct( $structures ) {
		global $EP_WORLDPRESS_INTERFACE;
		$this->WP = & $EP_WORLDPRESS_INTERFACE;
		$this -> structures = $structures;
	}
	function vote_shortcode() {
		if ( ! $this->WP->is_feed() ) {
			$kids  = $this->structures->get_child_by_taxonomy( $this->WP->get_post(), 'javaslat', 'vita' );
			$count = $kids->post_count;
			$rows  = '';
			while ( $kids->have_posts() ) {
				$kids->the_post();
				$rows .= sprintf( self::ALTERNATIVE_ROW, $this->WP->get_the_title(), $this->WP->get_post()->post_name );
			}
			$this->WP->wp_reset_postdata();
			$form = sprintf( self::FORM_HTML, $this->WP->get_the_id( $this->WP->get_post() ), $rows );
				return $form;
		} else {
				return $this->WP->__( 'Figyelem: A problémafelvetésben szavazás van. Látogasson el az oldalra!', 'ep' );
		}
	}

	function enqueue_scripts() {
		$this->WP->wp_enqueue_script( 'ep-vote', $this->WP->plugin_dir_url( __FILE__ ) . 'assets/js/voteslider.js', array(), EP_VERSION, false );
		$this->WP->wp_enqueue_style( 'ep-vote-css', $this->WP->plugin_dir_url( __FILE__ ) . 'assets/css/voteslider.css' );
	}

	function vote_submit() {
		$this->WP->echo('hello!');
		$this->WP->echo($this->WP->get_POST_data()['data']);
		$this->WP->wp_die();
	}

	function init() {
		$this->WP->add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		$this->WP->add_shortcode( 'vote', array( $this, 'vote_shortcode' ) );
		$this->WP->add_action( 'wp_ajax_ep_vote_submit', array( $this, 'vote_submit' ) );
	}

}

