<?php
function get_parent_szakkol($post)
{
	$term_list = wp_get_post_terms($post->ID, 'szakkoli', array("fields" => "all"));

	foreach($term_list as $term_single) {
		echo 'A <a href="' . get_site_url() . '/szakkolegium/' . $term_single->slug . '">'. $term_single->name . '</a> alatt van.'; //do something here
	}
}

function get_posts_for_szakkol($post_name, $post_type)
{
	    $args = array('post_type' => $post_type,
		'posts_per_page'=>-1,
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'szakkoli',
	                'field' => 'slug',
	                'terms' => $post_name,
	            ),
	        ),
	     );
	     $loop = new WP_Query($args);
	     return $loop;
}

function is_direct_child($parent_slug) {
	$direct=0;
	$defaults = array('fields' => 'names');
	foreach(wp_get_object_terms(get_the_ID(), 'szakkoli', $args) as $term) {
		if ($term->slug == $parent_slug) {
			$direct = 1;
		}
	};
	return $direct;
}
function list_posts_for($loop, $header_string, $parent_slug)
{
	if($loop->have_posts()) {
		echo $header_string;
		while($loop->have_posts()) : $loop->the_post();
			if (is_direct_child($parent_slug)) {
				echo '<a href="'.get_permalink().'">'.get_the_title().'</a><br>';
			};
		endwhile;
	}
	wp_reset_postdata();
}

function list_assets($post_name, $post_type, $header_string)
{
	$loop = get_posts_for_szakkol($post_name, $post_type);
	list_posts_for($loop, $header_string, $post_name);
}

function update_custom_terms($post_id) {

  if ( 'szakkolegium' != get_post_type($post_id)) {
    return;
  }

  // don't create or update terms for system generated posts
  if (get_post_status($post_id) == 'auto-draft') {
    return;
  }
    
  /*
  * Grab the post title and slug to use as the new 
  * or updated term name and slug
  */
  $term_title = get_the_title($post_id);
  $term_slug = get_post( $post_id )->post_name;

  /*
  * Check if a corresponding term already exists by comparing 
  * the post ID to all existing term descriptions. 
  */
  $existing_terms = get_terms('szakkoli', array(
    'hide_empty' => false
    )
  );

  foreach($existing_terms as $term) {
    if ($term->description == $post_id) {
      //term already exists, so update it and we're done
      wp_update_term($term->term_id, 'szakkoli', array(
        'name' => $term_title,
        'slug' => $term_slug
        )
      );
      return;
    }
  }

  /* 
  * If we didn't find a match above, this is a new post, 
  * so create a new term.
  */
  wp_insert_term($term_title, 'szakkoli', array(
    'slug' => $term_slug,
    'description' => $post_id
    )
  );
}

//run the update function whenever a post is created or edited
add_action('save_post', 'update_custom_terms');
?>
