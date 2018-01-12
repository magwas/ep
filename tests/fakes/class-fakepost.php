<?php declare(strict_types=1);
class FakePost {
	function __construct( $data ) {
		$this->data = $data;
		if ( ! isset( $data['ID'] ) ) {
			$this->ID = rand();
		} else {
			$this->ID = $data['ID'];
		}
		$this->post_name  = $data['post_name'];
		$this->post_title = $data['post_title'];
		$this->post_type  = $data['post_type'];
		if ( isset( $data['post_author'] ) ) {
			$this->post_author = $data['post_author'];
		} else {
			$this->post_author = ( FakeWp::$instance )->wp_get_current_user()->ID;
		}
		if ( ! isset( $data['terms'] ) ) {
			$this->terms = [];
			return;
		}
		foreach ( $data['terms'] as $term_id ) {
			$the_term                             = ( FakeWp::$instance )->allterms[ $term_id ];
			$this->terms[ $the_term->taxonomy ][] = $the_term;
		}
	}
	function get_terms( $tax ) {
		if ( ! isset( $this->terms[ $tax ] ) ) {
			return [];
		}
			return $this->terms[ $tax ];
	}
	function set_terms( $tax, $terms ) {
		$ids = [];
		foreach ( $terms as $term ) {
			$id                    = rand();
			$ids[]                 = $id;
			$args                  = array(
				$id,
				$tax,
				'name'        => $term,
				'slug'        => $term,
				'description' => $term,
			);
			$this->terms[ $tax ][] = new FakeTerm( $args );
		}
		return $ids;
	}
}
