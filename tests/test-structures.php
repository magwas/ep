<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
declare(strict_types=1);

require_once 'ep/class-structures.php';
require_once 'tests/class-wptestcase.php';
require_once 'tests/class-testdata.php';

class ElektoriparlamentStructuresTest extends WPTestCase {

	public function setUp() {
		parent::setUp();
		$this->instance = new Structures();
		$this->setData( ( new TestData() )->test_data );
	}

	public function test_init() {
		$this->instance->init();
		$this->assertActionAdded( 'save_post', array( $this->instance, 'update_custom_terms' ) );
	}

	public function test_get_parent_by_taxonomy() {
		$post   = $this->wp->get_post( 3 );
		$result = $this->instance->get_parent_by_taxonomy( $post, 'vita', 'A <a href="%s/problem/%s">%s</a> megoldasi javaslata.' );
		$this->assertEquals( 'A <a href="http://example.org/problem/vote">A vote</a> megoldasi javaslata.', $result );
	}

	public function test_get_parent_by_taxonomy_szakkol_case() {
		$post   = $this->wp->get_post( 1 );
		$result = $this->instance->get_parent_by_taxonomy( $post, 'szakkoli', 'A <a href="%s/problem/%s">%s</a> szakkolihoz tartozik.' );
		$this->assertEquals( 'A <a href="http://example.org/problem/slug-6">An unknown post</a> szakkolihoz tartozik.', $result );
	}

	public function test_get_children_by_taxonomy() {
		$expected = 'Header
href=http://example.org/?post_type=javaslat&p=2, title=title_2, image=<img src="http://example.org/wp-content/uploads//thumbnail_2.png" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" />
href=http://example.org/?post_type=javaslat&p=4, title=title_4, image=<img src="http://example.org/wp-content/uploads//thumbnail_4.png" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" />
href=http://example.org/?post_type=javaslat&p=5, title=title_5, image=<img src="http://example.org/wp-content/uploads//thumbnail_5.png" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" />
';
		$post     = $this->wp->get_post( 1 );
		$result   = $this->instance->list_assets_by_taxonomy( $post, 'javaslat', "Header\n", 'vita', "href=%s, title=%s, image=%s\n" );
		$this->assertEquals( $expected, $result );
	}

	public function test_get_children_by_taxonomy_with_empty() {
		$expected = '';
		$post     = $this->wp->get_post( 2 );
		$this->instance->list_assets_by_taxonomy( $post, 'prob', "Header\n", 'vita', "href=%s, title=%s, image=%s\n" );
		$this->assertEquals( $expected, $this->wp->output );
	}

	public function test_update_custom_terms_updates_if_needed() {
		$this->instance->update_custom_terms( 1 );
		$found = false;
		foreach ( $this->wp->get_terms( 'vita', array() ) as $term ) {
			if ( 'vote' == $term->slug &&
				'1' == $term->description &&
				'title_1' == $term->name ) {
					$found = true;
			}
		}
		$this->assertTrue( $found );
	}

	public function test_update_custom_terms_inserts_if_needed() {
		$this->instance->update_custom_terms( 8 );
		$found = false;
		$terms = $this->wp->get_terms( 'szakkoli', array() );
		foreach ( $terms as $term ) {
			if ( 'slug-8' == $term->slug &&
				'8' == $term->description &&
				'title_8' == $term->name ) {
				$found = true;
			}
		}
		$this->assertTrue( $found );
	}

	public function test_unknown_post_type_does_not_change_taxonomy() {
		$this->instance->update_custom_terms( 7 );
		$this->assertFalse( $this->wp->updated_tax );
	}
}

