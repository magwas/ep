<?php // phpcs:disable Squiz.Commenting

class Structures {
    function __construct() {
        global $EP_WORLDPRESS_INTERFACE;
        $this->WP = & $EP_WORLDPRESS_INTERFACE;
    }
	public function get_parent_by_taxonomy( $post, $taxname, $fmt ) {
		$term_list = $this->WP->wp_get_post_terms( $post->ID, $taxname, array( 'fields' => 'all' ) );
		$ret = '';
		foreach ( $term_list as $term_single ) {
			$ret .= sprintf( $fmt, $this->WP->get_site_url(), $term_single->slug, $term_single->name );
		}
		return $ret;
	}

	public function get_child_by_taxonomy( $post, $post_type, $tax_name ) {
			$args = array(
				'post_type'      => $post_type,
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy' => $tax_name,
						'field'    => 'slug',
						'terms'    => [$post->post_name],
					),
				),
			);
			$loop = $this->WP->WP_Query( $args );
			return $loop;
	}

	private function list_posts_for( $loop, $header_string, $fmt ) {
		if ( ! $loop->have_posts() ) {
			return;
		}
		$ret = $header_string;
		while ( $loop->have_posts() ) :
			$ret .= $this->listOnePost ( $loop, $fmt );
		endwhile;
		$this->WP->wp_reset_postdata();
		return $ret;
	}

	private function listOnePost($loop, $fmt) {
		$loop->the_post();
		$post=$this->WP->get_post();
		return sprintf( $fmt,
				$this->WP->get_post_permalink($post),
				$post->post_title,
				$this->WP->get_the_post_thumbnail($post)
				);
	}


	public function list_assets_by_taxonomy( $post, $post_type, $header_string, $tax_name, $fmt ) {
		$loop = $this->get_child_by_taxonomy( $post, $post_type, $tax_name );
		return $this->list_posts_for( $loop, $header_string, $fmt );
	}

	public function update_custom_terms( $post_id ) {
        $post = $this->WP->get_post($post_id);
		$post_type = $this->WP->get_post_type( $post );
		$tax_type  = $this->figureOutTaxonomyType ( $post_type );
		if ('' == $tax_type) {
			return;
		}

		$term_title = $post->post_title;
		$term_slug  = $post->post_name;
		$existing_terms = $this->WP->get_terms( $tax_type, ['hide_empty' => false]);

		foreach ( $existing_terms as $term ) {
			if($this->mayUpdateTerm ( $post, $term, $tax_type, $term_title, $term_slug )) {
				return;
			}
		}

		$this->WP->wp_insert_term($term_title, $tax_type,
			[
				'slug'        => $term_slug,
				'description' => $post_id
			]
		);
	}
	private function mayUpdateTerm($post, $term, $tax_type, $term_title, $term_slug) {
		if ( $term->description == $post->ID ) {
			$this->WP->wp_update_term(
				$term->term_id, $tax_type, array(
					'name' => $term_title,
					'slug' => $term_slug,
				)
			);
			return true;
		}
		return false;
	}

	private function figureOutTaxonomyType($post_type) {
		$tax_type  = '';
		if ( 'szakkolegium' == $post_type ) {
			$tax_type = 'szakkoli';
		} elseif ( 'problem' == $post_type ) {
			$tax_type = 'vita';
		}
		return $tax_type;
	}

	function init() {
		$this->WP->add_action( 'save_post', array( $this, 'update_custom_terms' ) );
	}

}

