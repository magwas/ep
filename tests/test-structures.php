<?php // phpcs:disable Squiz.Commenting
//declare(strict_types=1);

require_once 'ep/class-structures.php';
require_once 'tests/class-wptestcase.php';
require_once 'tests/class-testdata.php';

class ElektoriparlamentStructuresTest extends WPTestCase {

	public function setUp() {
		parent::setUp();
        $this->instance = new Structures();
		$this->setData((new TestData())->testData);
	}

	public function test_init() {
        $this->instance->init();
        $this->assertActionAdded( 'save_post', array( $this->instance, 'update_custom_terms' ) );
	}

    public function test_get_parent_by_taxonomy() {
        $post=$this->WP->get_post(3);
        $this->instance->get_parent_by_taxonomy( $post, 'vita', 'A <a href="%s/problem/%s">%s</a> megold<C3><A1>si javaslata.' );
        $this->assertEquals('A <a href="http://example.com/problem/vote">A vote</a> megold<C3><A1>si javaslata.', $this->WP->output);
    }
    
    public function test_get_children_by_taxonomy() {
    	$expected = "Header\nhref=http://example.com/slug_2, title=title_2, image=/thumbnail_2.png\n" .
			"href=http://example.com/slug_4, title=title_4, image=/thumbnail_4.png\n" .
			"href=http://example.com/slug_5, title=title_5, image=/thumbnail_5.png\n";
        $post=$this->WP->get_post(1);
        $this->WP->_set_query_result(array(2,4,5));
    	$this->instance->list_assets_by_taxonomy( $post, 'szakkolegium', "Header\n", 'szakkoli', "href=%s, title=%s, image=%s\n" );
    	$this->assertEquals($expected, $this->WP->output);
    }

    public function test_get_children_by_taxonomy_with_empty() {
    	$expected = "";
    	$post=$this->WP->get_post(2);
    	$this->WP->_set_query_result([]);
    	$this->instance->list_assets_by_taxonomy( $post, 'szakkolegium', "Header\n", 'szakkoli', "href=%s, title=%s, image=%s\n" );
    	$this->assertEquals($expected, $this->WP->output);
    }
    
    public function test_update_custom_terms_updates_if_needed() {
    	$this->instance->update_custom_terms(1);
    	$found = false;
    	foreach ($this->WP->get_terms("vita", array()) as $term) {
			if($term->slug == 'vote' && $term->description == '1' && $term->name == 'title_1')
				$found = true;
    	}
    	$this->assertTrue($found);
    }

    public function test_update_custom_terms_inserts_if_needed() {
    	$this->instance->update_custom_terms(6);
    	$found = false;
    	foreach ($this->WP->get_terms("szakkoli", array()) as $term) {
    		if($term->slug == 'slug_6' && $term->description == '6' && $term->name == 'title_1')
    			$found = true;
    	}
    	$this->assertTrue($found);
    }
    
    public function test_unknown_post_type_does_not_change_taxonomy() {
    	$this->instance->update_custom_terms(7);
    	$this->assertFalse($this->WP->updated_tax);
    }
}

