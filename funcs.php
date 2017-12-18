<?php
function get_parent_szakkol($post)
{
	$term_list = wp_get_post_terms($post->ID, 'szakkoli', array("fields" => "all"));

	foreach($term_list as $term_single) {
		echo 'A <a href="' . get_site_url() . '/szakkolegium/' . $term_single->slug . '">'. $term_single->name . '</a> alatt van.'; //do something here
	}
}

function get_child_by_taxonomy($post_name, $post_type, $tax_name)
{
	    $args = array('post_type' => $post_type,
		'posts_per_page'=>-1,
	        'tax_query' => array(
	            array(
	                'taxonomy' => $tax_name,
	                'field' => 'slug',
	                'terms' => $post_name,
	            ),
	        ),
	     );
	     $loop = new WP_Query($args);
	     return $loop;
}

function list_posts_for($loop, $header_string, $parent_slug)
{
	if($loop->have_posts()) {
		echo $header_string;
		while($loop->have_posts()) : $loop->the_post();
			echo '<a href="'.get_permalink().'">'.get_the_title().'</a><br>';
		endwhile;
	}
	wp_reset_postdata();
}

function list_assets_by_taxonomy($post_name, $post_type, $header_string, $tax_name)
{
	$loop = get_child_by_taxonomy($post_name, $post_type, $tax_name);
	list_posts_for($loop, $header_string, $post_name);
}

function update_custom_terms($post_id) {

  $post_type = get_post_type($post_id);
  $tax_type = '';
  if ( 'szakkolegium' == $post_type) {
    $tax_type='szakkoli';
  } else if ( 'problem' == $post_type ) {
    $tax_type='vita';
  } else {
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
  $existing_terms = get_terms($tax_type, array(
    'hide_empty' => false
    )
  );

  foreach($existing_terms as $term) {
    if ($term->description == $post_id) {
      //term already exists, so update it and we're done
      wp_update_term($term->term_id, $tax_type, array(
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
  wp_insert_term($term_title, $tax_type, array(
    'slug' => $term_slug,
    'description' => $post_id
    )
  );
}

//run the update function whenever a post is created or edited
add_action('save_post', 'update_custom_terms');
?>
