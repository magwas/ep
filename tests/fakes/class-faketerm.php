<?php
class FakeTerm {
	function __construct($args) {
		$this->term_id = $args[0];
		$this->taxonomy=$args[1];
		$this->slug = $args['slug'];
		$this->description = $args['description'];
		$this->name = $args['name'];
	}
	function update($args) {
		foreach ($args as $key => $value) {
			$this->$key = $value;
		}
	}
}