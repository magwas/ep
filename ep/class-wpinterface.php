<?php declare(strict_types=1);
class WPInterface {
	function wp_insert_post( array $postarr ) {
		return wp_insert_post( $postarr, true );
	}

	function add_action( $name, $value ) {
		return add_action( $name, $value );
	}
	function has_action( $name, $value ) {
		return has_action( $name, $value );
	}
	function add_shortcode( $name, $value ) {
		return add_shortcode( $name, $value );
	}
	function shortcode_exists( $name ) {
		return shortcode_exists( $name );
	}
	function add_filter( $name, $value ) {
		return add_filter( $name, $value );
	}
	function has_filter( $name, $value ) {
		return has_filter( $name, $value );
	}
	function wp_enqueue_script( $name, $path, $args, $version ) {
		return wp_enqueue_script( $name, $path, $args, $version );
	}
	function wp_script_is( $name, $what ) {
		return wp_script_is( $name, $what );
	}
	function wp_enqueue_style( $name, $path ) {
		return wp_enqueue_style( $name, $path );
	}
	function wp_style_is( $name, $what ) {
		return wp_style_is( $name, $what );
	}

	function plugin_dir_url( $file ) {
		return plugin_dir_url( $file );
	}
	function wp_get_post_terms( $id, $taxname ) {
		return wp_get_post_terms( $id, $taxname );
	}

	function wp_set_post_terms( $post_id, $terms, $taxonomy ) {
		return wp_set_post_terms( $post_id, $terms, $taxonomy );
	}

	function get_post( $num = 0 ) {
		return get_post( $num );
	}

	function wp_publish_post( $post_id ) {
		return wp_publish_post( $post_id );
	}

	function get_site_url() {
		return get_site_url();
	}

	function echo( $string ) {
		echo $string;
	}

	function wp_query( $args ) {
		return new WP_QUERY( $args );
	}

	function get_post_permalink( $post ) {
		return get_post_permalink( $post );
	}

	function get_the_post_thumbnail( $post ) {
		return get_the_post_thumbnail( $post );
	}
	function wp_reset_postdata() {
		return wp_reset_postdata();
	}
	function get_post_type( $post_id ) {
		return get_post_type( $post_id );
	}
	function get_terms( $tax_type, $args ) {
		return get_terms( $tax_type, $args );
	}
	function wp_insert_term( $term_title, $tax_type, $args ) {
		return wp_insert_term( $term_title, $tax_type, $args );
	}
	function wp_update_term( $term_id, $tax_type, $args ) {
		return wp_update_term( $term_id, $tax_type, $args );
	}
	function is_feed() {
		return is_feed();
	}
	//phpcs:disable WordPress.WP.I18n
	function __( $text, $class ) {
		return __( $text, $class );
	}
	function get_post_data() {
		global $_POST;
		return $_POST['data'];
	}
	function wp_die() {
		wp_die();
	}
	function wp_create_user( $username, $password ) {
		return wp_create_user( $username, $password );
	}
	function wp_get_current_user() {
		return wp_get_current_user();
	}
	function get_user_meta( $user_id, $meta ) {
		return get_user_meta( $user_id, $meta );
	}
	function update_user_meta( $user_id, $meta, $value ) {
		return update_user_meta( $user_id, $meta, $value );
	}
	function wp_set_current_user( $user_id ) {
		return wp_set_current_user( $user_id );
	}
}
