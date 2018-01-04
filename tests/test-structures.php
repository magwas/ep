<?php // phpcs:disable Squiz.Commenting
//declare(strict_types=1);

require_once 'ep/class-structures.php';
require_once 'tests/class-wptestcase.php';

class ElektoriparlamentVoteTest extends WPTestCase {


	public function setUp() {
		parent::setUp();
        $this->instance = new Structures();
        $this->setData(array(
            'posts' => array(
                1 => array(
                    'slug' => 'vote',
                    'title' => 'title_1',
                	'thumbnail' => '/thumbnail_1.png',
                    'type' => 'problem'
                ),
                2 => array(
                    'slug' => 'slug_2',
                    'title' => 'title_2',
                    'type' => 'solution',
                	'thumbnail' => '/thumbnail_2.png',
                	'terms' => array('szakkolegium' => array(new FakeTerm('vote', 'A vote')))
                ),
                3 => array(
                    'slug' => 'slug_3',
                    'title' => 'title_3',
                    'type' => 'solution',
                	'thumbnail' => '/thumbnail_3.png',
                	'terms' => array('vita' => array(new FakeTerm('vote', 'A vote')))
                ),
                4 => array(
                    'slug' => 'slug_4',
                    'title' => 'title_4',
                	'thumbnail' => '/thumbnail_4.png',
                	'type' => 'solution',
                    'parent' => 'vote',
                    'terms' => array('szakkolegium' => array(new FakeTerm('vote', 'A vote')))
                ),
                5 => array(
                    'slug' => 'slug_5',
                    'title' => 'title_5',
                	'thumbnail' => '/thumbnail_5.png',
                	'type' => 'solution',
                    'parent' => 'vote',
                    'terms' => array('szakkolegium' => array(new FakeTerm('vote', 'A vote')))
                )),
            "currentpost" => 1,
            )
        );
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

}

