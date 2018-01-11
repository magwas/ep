<?php
class FakeUser {
	function __construct($args=[]) {
		$this->ID = rand();
		foreach($args as $key => $value ) {
			$this->$key = $value;
		}
	}
	function get_user_meta($meta) {
		return $this->user_meta[$meta];
	}
	function update_meta($meta, $value) {
		$this->user_meta[$meta] = [$value];
	}
}
