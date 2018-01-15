<?php declare(strict_types=1);
class FakeUser {
	function __construct( $args = [] ) {
		$this->meta  = [];
		$this->ID    = rand();
		$this->roles = [ 'subscriber' ];
		foreach ( $args as $key => $value ) {
			$this->$key = $value;
		}
	}
	function get_user_meta( $meta ) {
		if ( ! isset( $this->user_meta[ $meta ] ) ) {
			return [];
		}
		return $this->user_meta[ $meta ];
	}
	function update_meta( $meta, $value ) {
		$this->user_meta[ $meta ] = [ $value ];
	}
}
