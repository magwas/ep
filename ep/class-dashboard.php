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

    function init() {
    }
	function unauthenticated( $user ) {
		return 0 == $user->ID;
	}

	function has_assurance( $user ) {
		$assurances = get_user_meta( $user->ID, 'eDemoSSO_assurances' );
		return is_array( $assurances ) && ( in_array( '["magyar"]', $assurances ) || in_array( '["emagyar"]', $assurances ) );
	}

	function did_accept( $user ) {
		$accepted = get_user_meta( $user->ID, 'accepted_the_rules', true );
		return $accepted;
	}

	function show_dashboard() {
		echo self::DASHBOARD_HEADER;
		$this->show_dashboard_content();
		echo self::DASHBOARD_FOOTER;
	}

	function show_dashboard_content() {
		$user = wp_get_current_user();
		if ( $this->unauthenticated( $user ) ) {
			echo self::LOGIN;
			return;
		}
		$name = $user->display_name;
		if ( ! $this->did_accept( $user ) ) {
			echo sprintf( self::ACCEPT_THE_RULES, $name );
			return;
		}
		if ( ! $this->has_assurance( $user ) ) {
			echo sprintf( self::GET_ASSURANCE, $name );
			return;
		}
		echo sprintf( self::YOU_ARE_MEMBER, $name );
	}

}

