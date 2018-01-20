<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
declare(strict_types = 1);

include_once 'ep/class-dashboard.php';
include_once 'ep/class-szakkol.php';
require_once 'tests/class-wptestcase.php';
require_once 'tests/class-testdata.php';

class JoinSzakkolTest extends WPTestCase {


	public function setUp() {
		parent::setUp();
		$this->dashboard = new Dashboard();
		$this->setData( ( new TestData() )->test_data );
		$this->szakkol = new Szakkol();
	}

	public function test_dashboard_contains_the_join_szakkol_button_in_a_szakkol_for_full_members() {
		$this->wp->wp_set_current_user( 4 );
		$args = array(
			'post_type'      => 'szakkolegium',
			'posts_per_page' => 1,
		);
		$loop = $this->wp->wp_query( $args );
		$loop->the_post();
		$post = $this->wp->get_post();
		$this->dashboard->show_dashboard();
		$button_string = sprintf( Dashboard::JOIN_BUTTON, $post->post_name );
		$this->assertstringContains( $button_string, $this->wp->output );
	}

	public function test_dashboard_contains_no_join_szakkol_button_in_a_szakkol_for_non_full_members() {
		$this->wp->wp_set_current_user( 2 );
		$args = array(
			'post_type'      => 'szakkolegium',
			'posts_per_page' => 1,
		);
		$loop = $this->wp->wp_query( $args );
		$loop->the_post();
		$post = $this->wp->get_post();
		$this->dashboard->show_dashboard();
		$button_string = sprintf( Dashboard::JOIN_BUTTON, $post->post_name );
		$this->assertstringNotContains( $button_string, $this->wp->output );
	}

	public function test_dashboard_contains_no_join_szakkol_button_in_another_post_type() {
		$this->wp->wp_set_current_user( 4 );
		$args = array(
			'post_type'      => 'problem',
			'posts_per_page' => 1,
		);
		$loop = $this->wp->wp_query( $args );
		$loop->the_post();
		$post = $this->wp->get_post();
		$this->dashboard->show_dashboard();
		$button_string = sprintf( Dashboard::JOIN_BUTTON, $post->post_name );
		$this->assertstringNotContains( $button_string, $this->wp->output );
	}

	public function test_dashboard_contains_no_join_szakkol_button_in_a_szakkol_for_joined_members() {
		$this->wp->wp_set_current_user( 4 );
		$args = array(
			'post_type'      => 'szakkolegium',
			'posts_per_page' => 1,
		);
		$loop = $this->wp->wp_query( $args );
		$loop->the_post();
		$post = $this->wp->get_post();
		$this->wp->update_post_meta(
			$post->ID, 'members', [
				4,
			]
		);
		$this->dashboard->show_dashboard();
		$button_string = sprintf( Dashboard::JOIN_BUTTON, $post->post_name );
		$this->assertstringNotContains( $button_string, $this->wp->output );
	}

	public function test_szakkol_class_registers_ajax_ep_join_szakkol() {
		$this->szakkol->init();
		$this->assertActionAdded( 'wp_ajax_ep_join_szakkol', array( $this->szakkol, 'join_szakkol' ) );
	}

	public function test_join_szakkol_adds_user_id_to_member_list() {
		$this->wp->wp_set_current_user( 4 );
		$_POST = [ 'szakkol' => 'slug-8' ];
		try {
			$this->szakkol->join_szakkol();
		} catch ( wpDieException $e ) {
		};
		$wrapped_meta = $this->wp->get_post_meta( 8, 'members' );
		$members      = $wrapped_meta[0];
		$this->assertDied();
		$this->assertEquals( 200, $this->wp->status_code );
		$this->assertContains( 4, $members );
	}

	public function test_join_szakkol_adds_keeps_eisting_members_in_the_list() {
		$this->test_join_szakkol_adds_user_id_to_member_list();
		$this->wp->wp_set_current_user( 5 );
		$_POST = [ 'szakkol' => 'slug-8' ];
		$this->do_join_szakkol();
		$wrapped_meta = $this->wp->get_post_meta( 8, 'members' );
		$members      = $wrapped_meta[0];
		$this->assertDied();
		$this->assertEquals( 200, $this->wp->status_code );
		$this->assertContains( 4, $members );
		$this->assertContains( 5, $members );
	}

	private function do_join_szakkol() {
		try {
			$this->szakkol->join_szakkol();
		} catch ( wpDieException $e ) {
		};}


	public function test_join_szakkol_validates_szakkol_input_underscore() {
		global $_POST;
		$_POST = [ 'szakkol' => 'foo_1' ];
		$this->do_join_szakkol();
		$this->assertEquals( 400, $this->wp->status_code );
		$this->assertDied();
	}
	public function test_join_szakkol_validates_szakkol_input_empty() {
		$_POST = [];
		$this->do_join_szakkol();
		$this->assertEquals( 400, $this->wp->status_code );
	}
	public function test_join_szakkol_validates_szakkol_input_space() {
		$_POST = [ 'szakkol' => 'foo 1' ];
		$this->do_join_szakkol();
		$this->assertEquals( 400, $this->wp->status_code );
	}
	public function test_join_szakkol_validates_szakkol_nonexisting_szakkol() {
		$_POST = [ 'szakkol' => 'foo-1' ];
		$this->do_join_szakkol();
		$this->assertEquals( 400, $this->wp->status_code );
	}
	public function test_join_szakkol_validates_szakkol_other_posttype() {
		$_POST = [ 'szakkol' => 'vote' ];
		$this->do_join_szakkol();
		$this->assertEquals( 400, $this->wp->status_code );
	}
	public function test_join_szakkol_validates_szakkol_capital() {
		$_POST = [ 'szakkol' => 'vOte' ];
		$this->do_join_szakkol();
		$this->assertEquals( 400, $this->wp->status_code );
	}
	public function test_join_szakkol_validates_szakkol_backtick() {
		$_POST = [ 'szakkol' => 'v`te' ];
		$this->do_join_szakkol();
		$this->assertEquals( 400, $this->wp->status_code );
	}
	public function test_join_szakkol_validates_szakkol_semicolon() {
		$_POST = [ 'szakkol' => 'v;te' ];
		$this->do_join_szakkol();
		$this->assertEquals( 400, $this->wp->status_code );
	}
}


