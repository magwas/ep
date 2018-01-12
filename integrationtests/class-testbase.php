<?php declare(strict_types=1);


abstract class TestBase extends wp_UnitTestCase {

	protected function initialize() {
		$this->author_id = $this->wp->wp_create_user( 'canonical-author' + rand(), 'canonical-password' );
		$this->wp->wp_set_current_user( $this->author_id );
		$this->post_id = $this->wp->wp_insert_post(
			array(
				'post_name'  => 'comment_test',
				'post_title' => 'comment-test',
				'post_date'  => '2008-03-03 00:00:00',
				'post_type'  => 'post',
			)
		);
	}

	public function test___() {
		$this->assertEquals( 'lorum ipse', $this->wp->__( 'lorum ipse', 'ep' ) );
	}
	public function test_get_post_data() {
		global $_POST;
		$_POST['data'] = 'something';
		$this->assertEquals( 'something', $this->wp->get_post_data() );
	}
	public function test_is_feed() {
		$this->assertFalse( $this->wp->is_feed() );
	}
	public function test_user() {
		$user = $this->wp->wp_get_current_user();
		$this->assertEquals( $this->wp->get_post( $this->post_id )->post_author, $user->ID );
	}
	public function test_usermeta() {
		$user = $this->wp->wp_get_current_user();
		$this->wp->update_user_meta( $user->ID, 'metavar', 42 );
		$this->assertEquals( 42, $this->wp->get_user_meta( $user->ID, 'metavar' )[0] );
	}
	public function test_get_terms() {
		$this->wp->wp_insert_term(
			'foo', 'szakkol', [
				'slug'        => 'slugka',
				'description' => 'bar',
			]
		);
		$r = $this->wp->get_terms( 'szakkol', [ 'hide_empty' => false ] );
		$this->assertEquals( 1, count( $r ) );
		$this->assertEquals( 'slugka', $r[0]->slug );
		$this->assertEquals( 'bar', $r[0]->description );
		$this->assertEquals( 'foo', $r[0]->name );
		$r3 = $this->wp->wp_update_term(
			$r[0]->term_id, 'szakkol', array(
				'name' => 'newname',
				'slug' => 'newslug',
			)
		);
		$r2 = $this->wp->get_terms( 'szakkol', [ 'hide_empty' => false ] );
		$this->assertEquals( 'newname', $r2[0]->name );
		$this->assertEquals( 'newslug', $r2[0]->slug );
	}

	public function test_post_addition() {
		$post = $this->wp->get_post( $this->post_id );
		$this->assertEquals( $this->author_id, $post->post_author );
	}

	public function test_add_action() {
		$this->wp->add_action( 'foo', [ $this, 'test_add_action' ] );
		$this->assertEquals( 10, $this->wp->has_action( 'foo', [ $this, 'test_add_action' ] ) );
	}

	public function test_add_filter() {
		$this->wp->add_filter( 'foo', [ $this, 'test_add_filter' ] );
		$this->assertEquals( 10, $this->wp->has_filter( 'foo', [ $this, 'test_add_filter' ] ) );
	}

	public function test_add_shortcode() {
		$this->wp->add_shortcode( 'foo', [ $this, 'test_add_shortcode' ] );
		$this->assertEquals( 10, $this->wp->shortcode_exists( 'foo' ) );
	}

	public function test_enqueue_script() {
		$this->wp->wp_enqueue_script( 'foo', '/foo.php', [], '0.1' );
		$this->assertEquals( true, $this->wp->wp_script_is( 'foo', 'enqueued' ) );
	}

	public function test_enqueue_style() {
		$this->wp->wp_enqueue_style( 'foo', '/foo.php', [], '0.1' );
		$this->assertEquals( true, $this->wp->wp_style_is( 'foo', 'enqueued' ) );
	}

	public function test_plugin_dir_url() {
		$url = $this->wp->plugin_dir_url( 'foo' );
		$this->assertEquals( 'http://example.org/wp-content/plugins/', $url );
	}

	public function test_get_post_terms() {
		$r     = $this->wp->wp_set_post_terms( $this->post_id, [ 'egy' ], 'szakkol', true );
		$terms = $this->wp->wp_get_post_terms( $this->post_id, 'szakkol' );
		$this->assertEquals( $r[0], $terms[0]->term_id );
	}

	public function test_get_site_url() {
		$this->assertEquals( 'http://example.org', $this->wp->get_site_url() );
	}

	public function test_wp_query() {
		$r = $this->wp->wp_set_post_terms( $this->post_id, [ 'ketto' ], 'szakkol', true );
		$this->wp->wp_publish_post( $this->post_id );
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'szakkol',
					'field'    => 'slug',
					'terms'    => [ 'ketto' ],
				),
			),
		);
		$loop = $this->wp->wp_query( $args );
		$this->assertEquals( 1, $loop->post_count );
	}

	public function test_get_permalink() {
		$post = $this->wp->get_post( $this->post_id );
		$r    = $this->wp->get_post_permalink( $post );
		$this->assertEquals( 'http://example.org/?post_type=post&p=' . $post->ID, $r );
	}

	public function test_get_post_thumbnail() {
		$post = $this->wp->get_post( $this->post_id );
		$r    = $this->wp->get_the_post_thumbnail( $post );
		$this->assertEquals( '<img src="http://example.org/wp-content/uploads//tmp/foo.jpg" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" />', $r );
	}

}
