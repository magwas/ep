<?php declare(strict_types=1);

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
		global $_ep_wordpress_interface;
		$this->wp         = & $_ep_wordpress_interface;
		$this->structures = $structures;
	}
	function vote_shortcode() {
		if ( ! $this->wp->is_feed() ) {
			$post  = $this->wp->get_post();
			$kids  = $this->structures->get_child_by_taxonomy( $post, 'javaslat', 'vita' );
			$count = $kids->post_count;
			$rows  = '';
			while ( $kids->have_posts() ) {
				$kids->the_post();
				$kid   = $this->wp->get_post();
				$rows .= sprintf( self::ALTERNATIVE_ROW, $kid->post_title, $kid->post_name );
			}
			$this->wp->wp_reset_postdata();
			$form = sprintf( self::FORM_HTML, $post->ID, $rows );
				return $form;
		} else {
				return $this->wp->__( 'Figyelem: A problémafelvetésben szavazás van. Látogasson el az oldalra!', 'ep' );
		}
	}

	function enqueue_scripts() {
		$this->wp->wp_enqueue_script( 'ep-vote', $this->wp->plugin_dir_url( __FILE__ ) . 'assets/js/voteslider.js', array(), EP_VERSION, false );
		$this->wp->wp_enqueue_style( 'ep-vote-css', $this->wp->plugin_dir_url( __FILE__ ) . 'assets/css/voteslider.css' );
	}

	function vote_submit() {
		$this->wp->echo( 'hello!' );
		$this->wp->echo( $this->wp->get_post_data() );
		$this->wp->wp_die();
	}

	function init() {
		$this->wp->add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		$this->wp->add_shortcode( 'vote', array( $this, 'vote_shortcode' ) );
		$this->wp->add_action( 'wp_ajax_ep_vote_submit', array( $this, 'vote_submit' ) );
	}

}

