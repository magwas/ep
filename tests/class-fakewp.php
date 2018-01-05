<?php

class FakeTerm {
	function __construct($args) {
		$this->slug = $args['slug'];
		$this->description = $args['description'];
		$this->name = $args['name'];
		$this->term_id = rand();
	}
    function update($args) {
    	foreach ($args as $key => $value) {
    		$this->$key = $value;
    	}
    }
}
class FakePost {
    function __construct($data) {
    	$this->data = $data;
    	$this->ID = $data['ID'];
    	$this->post_name = $data['slug'];
    	$this->post_title = $data['title'];
    	$this->type = $data['type'];
    }
    function get_terms($tax, $args) {
    	return $this->data['terms'][$tax];
    }
}

class FakeQuery {
	public $post_count;
	function __construct($args,$wp) {
		$this->wp = $wp;
		$obj = new ArrayObject( $wp->post_list );
		$this->it = $obj->getIterator();
		$this->update_post_count ();
	}
	private function update_post_count() {
		$post_count = $this->it->count();
	}

	function have_posts() {
		return $this->it->valid();
	}
	function the_post() {
		$p = $this->it->current();
		$post = $this->wp->posts[$p];
		$this->wp->post = $post;
		$this->it->next();
		$this->update_post_count ();
	}
}

class FakeWP {

    function __construct($testdata) {
        $this->buildPosts ( $testdata );
    	$currentpost = $testdata['currentpost'];
        $this->post = $this->posts[$currentpost];
        $this->output='';
        $this->buildTaxonomies ( $testdata );
    	$this->updated_tax=false;
    	$this->is_feed=false;
    }
	private function buildPosts($testdata) {
		foreach($testdata['posts'] as $key => $post) {
			$post['ID'] = $key;
			$this->posts[$key] = new FakePost($post);
		}
	}
    private function buildTaxonomies($testdata) {
		$this->taxonomy = [];
        foreach($testdata['taxonomy'] as $key=>$value) {
        	$this->buildOneTaxonomy ($key, $value);
        }
	}
	private function buildOneTaxonomy($key, $value) {
		$this->taxonomy[$key] = [];
		foreach ($value as $term) {
			$this->taxonomy[$key][$term->term_id] = $term;
		}
	}


    function _set_query_result($post_list) {
    	$this->post_list = $post_list;
    }
    function add_action($name,$value) {
        $this->actions[$name] = $value;
    }
    function add_shortcode($name,$value) {
    	$this->shortcodes[$name] = $value;
    }
    function wp_enqueue_script($name,$path,$args,$version) {
    	$this->scripts[$name] = [$path,$args,$version];
    }
    function wp_enqueue_style($name,$path) {
    	$this->scripts[$name] = $path;
    }
    
    function plugin_dir_url($file) {
    	$fixedPath = "ep/";
    	return "http://example.com/wp-content/plugins/".$fixedPath;
    }
    function wp_get_post_terms( $ID, $taxname, $args) {
    	return $this->posts[$ID]->get_terms($taxname,$args);
    }

    function get_post($num = null) {
    	if ($num) {
    		return $this->posts[$num];
    	}
        return $this->post;
    }
    
    function get_site_url() {
    	return "http://example.com";
    }
    
    function echo($string) {
    	$this->output .= $string;
    }
    
    function WP_QUERY($args) {
    	$this->post_old=$this->post;
    	return new FakeQuery($args, $this);
    }
    
    function get_permalink() {
    	return $this->get_site_url() . '/' . $this->post->post_name;
    }

    function get_the_title() {
    	return $this->post->post_title;
    }

    function get_the_id() {
    	return $this->post->ID;
    }
    
    function get_the_post_thumbnail() {
    	return $this->post->data['thumbnail'];
    }
    function wp_reset_postdata() {
    	$this->post=$this->post_old;
    }
    function get_post_type($post_id) {
    	return $this->posts[$post_id]->type;
    }
    function get_terms($tax_type, $args) {
    	return $this->taxonomy[$tax_type];
    }
    function wp_insert_term($term_title, $tax_type, $args) {
    	$args['name'] = $term_title;
    	$this->taxonomy[$tax_type][] = new FakeTerm($args);
    	$this->updated_tax=true;
    }
    function wp_update_term($term_id, $tax_type, $args) {
    	$this->taxonomy[$tax_type][$term_id]->update($args);
    	$this->updated_tax=true;
    }
    function is_feed() {
    	return $this->is_feed;
    }
    function __($str,$class) {
    	return $class.":".$str;
    }
    function get_POST_data() {
    	return ['data'=>'foo'];
    }
    function wp_die() {
    	$this->died=true;
    }
}
 