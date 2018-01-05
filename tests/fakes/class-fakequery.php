<?php
class FakeQuery {
	public $post_count;
	function __construct($args,$wp) {
		$this->wp = $wp;
		$res = [];
		foreach ($wp->posts as $post) {
			if( $post->type == $args['post_type'] &&
					$this->postContainsTerm($post, $args['tax_query'])
					) {
						$res[] = $post;
					}
						
		}
		$obj = new ArrayObject( $res );
		$this->it = $obj->getIterator();
		$this->update_post_count ();
	}
	private function postContainsTerm($post, $query) {
		foreach ($post->get_terms($query[0]['taxonomy'],[]) as $term ) {
			$field = $query[0]['field'];
			if ($term ->$field == $query[0]['terms'])
				return true;
		}
		return false;
	}
	private function update_post_count() {
		$post_count = $this->it->count();
	}

	function have_posts() {
		return $this->it->valid();
	}
	function the_post() {
		$p = $this->it->current()->ID;
		$post = $this->wp->posts[$p];
		$this->wp->post = $post;
		$this->it->next();
		$this->update_post_count ();
	}
}
