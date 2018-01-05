<?php
class FakeUriGenerator {
	function get_button_action($task) {
		if($task == 'register') {
			return "registerUri(blabla)";
		}
	}
}
