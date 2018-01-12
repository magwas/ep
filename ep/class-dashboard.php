<?php // phpcs:disable Squiz.Commenting

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
<script>
function accept_rules() {
        var query = {
                'action' : 'ep_accept_rules',
        };
        console.log(query);
        jQuery.post('/wp-admin/admin-ajax.php', query, function(response) {
                console.log('Got this from the server: ' + response);
		window.location.reload();
        });
}
</script>
Kedves %s, még nem vagy tag, ehhez el kell fogadnod a szabályainkat.<br\>
<button onclick="javascript:accept_rules()">Efogadom a szabályokat</button>
EOT;
	const GET_ASSURANCE    = <<<'EOT'
Kedves %s, a regisztrálásod sikerült, már csak egy lépés van hátra: <a href="/hogyan-szerzek-magyar-vagy-emagyar-igazolast" target="blank">szerezz "magyar" vagy "emagyar" igazolást!</a>
EOT;
	const YOU_ARE_MEMBER   = <<<'EOT'
Tag vagy, %s!
EOT;

	function __construct(  ) {
		global $EP_WORLDPRESS_INTERFACE;
		$this->WP = & $EP_WORLDPRESS_INTERFACE;
	}
	
    function init() {
        $this->WP->add_shortcode('acceptrules', array( $this, 'acceptrules_shortcode' ) );
        $this->WP->wp_enqueue_style( 'ep-css', $this->WP->plugin_dir_url( __FILE__ ) . 'assets/css/ep.css' );
        $this->WP->wp_enqueue_script( 'ep-js', $this->WP->plugin_dir_url( __FILE__ ) . 'assets/js/ep.js', array(), EP_VERSION, false  );
        
    }
	function unauthenticated( $user ) {
		return 0 == $user->ID;
	}

	function has_assurance( $user ) {
		$assurances = $this->WP->get_user_meta( $user->ID, 'eDemoSSO_assurances' );
		if (!is_array( $assurances ) || !isset($assurances[0]))
			return;
		$splitAssurances = json_decode($assurances[0]);
		return ( in_array( "magyar", $splitAssurances ) || in_array( "emagyar", $splitAssurances ) );
	}

	function did_accept( $user ) {
		$accepted = $this->WP->get_user_meta( $user->ID, 'accepted_the_rules', true );
		return $accepted;
	}

	function show_dashboard() {
		$this->WP->echo(self::DASHBOARD_HEADER);
		$this->show_dashboard_content();
		$this->WP->echo(self::DASHBOARD_FOOTER);
	}

	function show_dashboard_content() {
		$user = $this->WP->wp_get_current_user();
		if ( $this->unauthenticated( $user ) ) {
			$this->WP->echo(self::LOGIN);
			return;
		}
		$name = $user->display_name;
		if ( ! $this->did_accept( $user ) ) {
			$this->WP->echo(sprintf( self::ACCEPT_THE_RULES, $name ));
			return;
		}
		if ( ! $this->has_assurance( $user ) ) {
			$this->WP->echo(sprintf( self::GET_ASSURANCE, $name ));
			return;
		}
		$this->WP->echo(sprintf( self::YOU_ARE_MEMBER, $name ));
	}
	
	function acceptrules_shortcode() {
	    $user = $this->WP->wp_get_current_user();
	    if($this->WP->get_user_meta( $user->ID, 'accepted_the_rules' ) == []) {
	        return '<div class="accept-shortcode"><button onclick="javascript:accept_rules()">Efogadom a szabályokat</button></div>';
	    }
	    return '';
	}

}

