<?php

class FakeTerm {
	function __construct($slug, $name) {
		$this->slug = $slug;
		$this->name = $name;
	}
}
class FakePost {
    function __construct($data) {
    	$this->data = $data;
    	$this->ID = $data['ID'];
    	$this->post_name = $data['title'];
    	$this->slug = $data['slug'];
    }
    function get_terms($tax, $args) {
    	return $this->data['terms'][$tax];
    }
}

class FakeQuery {
	function __construct($args,$wp) {
		$this->wp = $wp;
		$obj = new ArrayObject( $wp->post_list );
		$this->it = $obj->getIterator();
	}
	function have_posts() {
		return $this->it->valid();
	}
	function the_post() {
		$p = $this->it->current();
		echo "p=" . $p;
		$post = $this->wp->posts[$p];
		$this->wp->post = $post;
		$this->it->next();
	}
}

class FakeWP {

    function __construct($testdata) {
        foreach($testdata['posts'] as $key => $post) {
            $post['ID'] = $key;
            $this->posts[$key] = new FakePost($post);
        }
        $currentpost = $testdata['currentpost'];
        $this->post = $this->posts[$currentpost];
        $this->output='';
    }
    function _set_query_result($post_list) {
    	$this->post_list = $post_list;
    }
    function add_action($name,$value) {
        $this->actions[$name] = $value;
    }
    
    function wp_get_post_terms( $ID, $taxname, $args) {
    	return $this->posts[$ID]->get_terms($taxname,$args);
    }

    function get_post($num) {
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
    	return new FakeQuery($args, $this);
    }
    
    function get_permalink() {
    	return $this->get_site_url() . '/' . $this->post->slug;
    }

    function get_the_title() {
    	return $this->post->post_name;
    }

    function get_the_post_thumbnail() {
    	return $this->post->data['thumbnail'];
    }
    function wp_reset_postdata() {
    	
    }
}
