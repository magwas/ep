<?php declare(strict_types=1);

require_once 'class-fakeurigenerator.php';
require_once 'class-faketerm.php';
require_once 'class-fakepost.php';
require_once 'class-fakequery.php';
require_once 'class-fakeuser.php';

class Fakewp {

	static $instance;
	function __construct( $testdata ) {
		self::$instance = $this;
		$this->build_users( $testdata );
		$this->current_user = $this->users[1];
		$this->build_taxonomies( $testdata );
		$this->build_posts( $testdata );
		$currentpost        = $testdata['currentpost'];
		$this->post         = $this->posts[ $currentpost ];
		$this->output       = '';
		$this->updated_tax  = false;
		$this->is_feed      = false;
		$this->current_user = $this->users[0];
		$this->status_code  = 200;
		if ( ! class_exists( 'wpDieException' ) ) {
			eval( 'class wpDieException extends Exception { }' ); // phpcs:disable Squiz.PHP.Eval.Discouraged
			$GLOBALS['wpDieException'] = 'wpDieException';
		}
	}

	function status_header( $status ) {
		$this->status_code = '' . $status;
	}
	private function build_users( $testdata ) {
		foreach ( $testdata['users'] as $key => $user ) {
			$user['ID']          = $key;
			$this->users[ $key ] = new FakeUser( $user );
		}
	}
	private function build_posts( $testdata ) {
		foreach ( $testdata['posts'] as $key => $post ) {
			$post['ID']          = $key;
			$this->posts[ $key ] = new FakePost( $post );
		}
	}
	private function build_taxonomies( $testdata ) {
		$this->taxonomy = [];
		foreach ( $testdata['terms'] as $key => $term ) {
			$the_term                               = new FakeTerm( $term );
			$this->taxonomy[ $term[1] ][ $term[0] ] = $the_term;
			$this->allterms[ $the_term->term_id ]   = $the_term;
		}
	}

	function wp_insert_post( $postarr ) {
		$post                     = new FakePost( $postarr );
		$this->posts[ $post->ID ] = $post;
		return $post->ID;
	}
	function add_action( $name, $value ) {
		$this->actions[ $name ] = $value;
	}
	function has_action( $name, $value ) {
		if ( $this->actions[ $name ] == $value ) {
			return 10;
		}
	}
	function add_filter( $name, $value ) {
		$this->filters[ $name ] = $value;
	}
	function has_filter( $name, $value ) {
		if ( $this->filters[ $name ] == $value ) {
			return 10;
		}
	}
	function add_shortcode( $name, $value ) {
		$this->shortcodes[ $name ] = $value;
	}
	function shortcode_exists( $name ) {
		return isset( $this->shortcodes[ $name ] );
	}
	function wp_enqueue_script( $name, $path, $args, $version ) {
		$this->scripts[ $name ] = [ $path, $args, $version ];
	}
	function wp_script_is( $name, $what ) {
		return isset( $this->scripts[ $name ] );
	}
	function wp_enqueue_style( $name, $path ) {
		$this->styles[ $name ] = $path;
	}
	function wp_style_is( $name, $what ) {
		return isset( $this->styles[ $name ] );
	}

	function plugin_dir_url( $file ) {
		return 'http://example.org/wp-content/plugins/';
	}

	function get_post( $num = null ) {
		if ( $num ) {
			return $this->posts[ $num ];
		}
		return $this->post;
	}

	function wp_publish_post() {

	}

	function get_site_url() {
		return 'http://example.org';
	}

	function echo( $string ) {
		$this->output .= $string;
	}

	function wp_query( $args ) {
		$this->post_old = $this->post;
		return new FakeQuery( $args, $this );
	}

	function get_post_permalink( $post ) {
		return $this->get_site_url() . '/?post_type=' . $post->post_type . '&p=' . $post->ID;
	}

	function get_the_post_thumbnail( $post ) {
		return sprintf(
			'<img src="%s/wp-content/uploads/%s" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" />',
			$this->get_site_url(),
			$post->data['thumbnail']
		);
	}
	function wp_reset_postdata() {
		$this->post = $this->post_old;
	}
	function get_post_type( $post ) {
		return $post->post_type;
	}
	function get_terms( $tax_type, $args ) {
		$r = [];
		foreach ( $this->taxonomy[ $tax_type ] as $term ) {
			$r[] = $term;
		}
		return $r;
	}
	function wp_insert_term( $term_title, $tax_type, $args ) {
		$args['name'] = $term_title;
		$args[0]      = rand();
		$args[1]      = $tax_type;
		$term         = new FakeTerm( $args );
		$this->taxonomy[ $tax_type ][ $term->term_id ] = $term;
		$this->updated_tax                             = true;
		return [ 'term_id' => $args[0] ];
	}
	function wp_update_term( $term_id, $tax_type, $args ) {
		$this->taxonomy[ $tax_type ][ $term_id ]->update( $args );
		$this->updated_tax = true;
	}

	function wp_get_post_terms( $id, $taxname ) {
		return $this->posts[ $id ]->get_terms( $taxname );
	}

	function wp_set_post_terms( $postid, $terms, $tax, $pupdate ) {
		return $this->posts[ $postid ]->set_terms( $tax, $terms );
	}

	function is_feed() {
		return $this->is_feed;
	}

	//phpcs:disable WordPress.WP.I18n
	function __( $str, $class ) {
		return $str;
	}
	function get_post_data() {
		global $_POST;
		return $_POST;
	}
	function wp_die() {
		$this->died = true;
		throw new wpDieException( 'wp_die' );
	}

	function wp_create_user( $username, $password ) {
		$user_object                     = new FakeUser(
			[
				'display_name' => $username,
				'password'     => $password,
			]
		);
		$this->users[ $user_object->ID ] = $user_object;
		return $user_object->ID;
	}

	function wp_get_current_user() {
		return $this->current_user;
	}
	function get_user_meta( $user_id, $meta ) {
		return $this->users[ $user_id ]->get_user_meta( $meta );
	}
	function update_user_meta( $user_id, $meta, $value ) {
		$this->users[ $user_id ]->update_meta( $meta, $value );
	}
	function wp_set_current_user( $user_id ) {
		$this->current_user = $this->users[ $user_id ];
	}

	function update_post_meta( $postid, $key, $value ) {
		$post               = $this->get_post( $postid );
		$post->meta[ $key ] = $value;
	}

	function get_post_meta( $postid, $key ) {
		$post = $this->get_post( $postid );
		if ( ( ! isset( $post->meta ) ) || ( ! isset( $post->meta[ $key ] ) ) ) {
			return [];
		}
		return [ $post->meta[ $key ] ];
	}

	function get_posts( $args ) {
		$r     = [];
		$query = $this->wp_query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$r[] = $this->get_post();
			}
		}
		$this->wp_reset_postdata();
		return $r;
	}
}

