<?php

class Dashboard {

	const DASHBOARD_HEADER = <<<'EOT'
<div class="ep_dashboard" style="background-color:#E5E5FF;text-align: center;">
EOT;
	const DASHBOARD_FOOTER = <<<'EOT'
</div>
EOT;

	const LOGIN = <<<'EOT'
<button id="login_button">Jelentkezz be!</button>
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
	const GET_ASSURANCE = <<<'EOT'
Kedves %s, még nem vagy tag, <a href="regisztracio" target="blank">szerezz "magyar" igazolást!</a>
EOT;
	const YOU_ARE_MEMBER = <<<'EOT'
Tag vagy, %s!
EOT;

	function unauthenticated($user) {
		return $user->ID == 0;
	}

	function has_assurance($user) {
		$assurances = get_user_meta($user->ID, 'eDemoSSO_assurances');
		return is_array($assurances) && (in_array('["magyar"]', $assurances) || in_array('["emagyar"]', $assurances));
	}

	function did_accept($user) {
		$accepted = get_user_meta($user->ID, 'accepted_the_rules', true);
		return $accepted;
	}

	function show_dashboard() {
		echo self::DASHBOARD_HEADER;
		self::show_dashboard_content();
		echo self::DASHBOARD_FOOTER;
	}

	function show_dashboard_content() {
		$user = wp_get_current_user();
		if (self::unauthenticated($user)) {
			echo self::LOGIN;
			return;
		}
		$name = $user->display_name;
		if(!self::did_accept($user)) {
			echo sprintf(self::ACCEPT_THE_RULES,$name);
			return;
		}
		if (!self::has_assurance($user)) {
			echo sprintf(self::GET_ASSURANCE,$name);
			return;
		}
		echo sprintf(self::YOU_ARE_MEMBER,$name);
	}

	function accept_rules() {
		$user = wp_get_current_user();
		update_user_meta( $user->ID, 'accepted_the_rules', 1);
		echo 'user=' . $user->ID;
		echo 'accepted=' . get_user_meta($user->ID,  'accepted_the_rules', true ) ;
		wp_die();
	}

}

add_action('wp_ajax_ep_accept_rules',Array('Dashboard','accept_rules'));
?>
