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
		$this -> structures = $structures;
	}
	function vote_shortcode() {
		global $post;
		if ( ! is_feed() ) {
			$kids  = $this->structures->get_child_by_taxonomy( $post, 'javaslat', 'vita' );
			$count = $kids->post_count;
			$rows  = '';
			while ( $kids->have_posts() ) {
				$kids->the_post();
				$rows .= sprintf( self::ALTERNATIVE_ROW, get_the_title(), $post->post_name );
			}
			wp_reset_postdata();
			$form = sprintf( self::FORM_HTML, get_the_id( $post ), $rows );
				return $form;
		} else {
				return __( 'Figyelem: A problémafelvetésben szavazás van. Látogasson el az oldalra!', 'ep' );
		}
	}

	function enqueue_scripts() {
		wp_enqueue_script( 'ep-vote', plugin_dir_url( __FILE__ ) . 'assets/js/voteslider.js', array(), EP_VERSION, false );
		wp_enqueue_style( 'ep-vote-css', plugin_dir_url( __FILE__ ) . 'assets/css/voteslider.css' );
	}

	function vote_submit() {
		echo 'hello!';
		echo $_POST['data'];
		wp_die();
	}

	function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_shortcode( 'vote', array( $this, 'vote_shortcode' ) );
		add_action( 'wp_ajax_ep_vote_submit', array( $this, 'vote_submit' ) );
	}

}

