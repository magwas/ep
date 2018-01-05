<?php
class FakePost {
	function __construct($data) {
		$this->data = $data;
		$this->ID = $data['ID'];
		$this->post_name = $data['slug'];
		$this->post_title = $data['title'];
		$this->type = $data['type'];
		if(!isset($data['terms'])) {
			$this->terms = [];
			return;
		}
		foreach ($data['terms'] as $term_id) {
			$theTerm = (FakeWp::$instance)->allterms[$term_id];
			$this->terms[$theTerm->taxonomy][] = $theTerm;
		}
	}
	function get_terms($tax, $args) {
		if(!isset($this->terms[$tax]))
			return [];
			return $this->terms[$tax];
	}
}
