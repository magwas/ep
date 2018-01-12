<?php declare(strict_types=1);
class FakeUriGenerator {
	function get_button_action( $task ) {
		if ( $task == 'register' ) {
			return 'registerUri(blabla)';
		}
	}
}
