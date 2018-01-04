<?php // phpcs:disable Squiz.Commenting

class Structures {
    function __construct() {
        global $EP_WORLDPRESS_INTERFACE;
        $this->WP = & $EP_WORLDPRESS_INTERFACE;
    }
	public function get_parent_by_taxonomy( $post, $taxname, $fmt ) {
		$term_list = $this->WP->wp_get_post_terms( $post->ID, $taxname, array( 'fields' => 'all' ) );
		foreach ( $term_list as $term_single ) {
			$this->WP->echo(sprintf( $fmt, $this->WP->get_site_url(), $term_single->slug, $term_single->name ));
		}
	}

	private function get_child_by_taxonomy( $post, $post_type, $tax_name ) {
			$args = array(
				'post_type'      => $post_type,
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy' => $tax_name,
						'field'    => 'slug',
						'terms'    => $post->post_name,
					),
				),
			);
			$loop = $this->WP->WP_Query( $args );
			return $loop;
	}

	private function list_posts_for( $loop, $header_string, $fmt ) {
		if ( $loop->have_posts() ) {
			$this->WP->echo($header_string);
			while ( $loop->have_posts() ) :
				$loop->the_post();
				$this->WP->echo(sprintf( $fmt,
						$this->WP->get_permalink(),
						$this->WP->get_the_title(),
						$this->WP->get_the_post_thumbnail()
						));
			endwhile;
		}
		$this->WP->wp_reset_postdata();
	}

	public function list_assets_by_taxonomy( $post, $post_type, $header_string, $tax_name, $fmt ) {
		$loop = $this->get_child_by_taxonomy( $post, $post_type, $tax_name );
		$this->list_posts_for( $loop, $header_string, $fmt );
	}

	function update_custom_terms( $post_id ) {

		$post_type = get_post_type( $post_id );
		$tax_type  = '';
		if ( 'szakkolegium' == $post_type ) {
			$tax_type = 'szakkoli';
		} elseif ( 'problem' == $post_type ) {
			$tax_type = 'vita';
		} else {
			return;
		}

		/*
		* Grab the post title and slug to use as the new
		* or updated term name and slug
		*/
		$term_title = get_the_title( $post_id );
		$term_slug  = get_post( $post_id )->post_name;

		/*
		* Check if a corresponding term already exists by comparing
		* the post ID to all existing term descriptions.
		*/
		$existing_terms = get_terms(
			$tax_type, array(
				'hide_empty' => false,
			)
		);

		foreach ( $existing_terms as $term ) {
			if ( $term->description == $post_id ) {
				// term already exists, so update it and we're done
				wp_update_term(
					$term->term_id, $tax_type, array(
						'name' => $term_title,
						'slug' => $term_slug,
					)
				);
				return;
			}
		}

		/*
		* If we didn't find a match above, this is a new post,
		* so create a new term.
		*/
		wp_insert_term(
			$term_title, $tax_type, array(
				'slug'        => $term_slug,
				'description' => $post_id,
			)
		);
	}

	function show_dashboard() {
		Dashboard::show_dashboard();
	}

	function init() {
		$this->WP->add_action( 'save_post', array( $this, 'update_custom_terms' ) );
	}

}

