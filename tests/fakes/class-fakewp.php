<?php

require_once 'class-fakeurigenerator.php';
require_once 'class-faketerm.php';
require_once 'class-fakepost.php';
require_once 'class-fakequery.php';
require_once 'class-fakeuser.php';

class FakeWP {

	static $instance;
    function __construct($testdata) {
    	self::$instance = $this;
        $this->buildTaxonomies ( $testdata );
    	$this->buildPosts ( $testdata );
        $this->buildUsers ( $testdata );
    	$currentpost = $testdata['currentpost'];
        $this->post = $this->posts[$currentpost];
        $this->output='';
    	$this->updated_tax=false;
    	$this->is_feed=false;
    	$this->current_user = $this->users[0];
    }
	private function buildUsers($testdata) {
		foreach($testdata['users'] as $key => $user) {
			$user['ID'] = $key;
			$this->users[$key] = new FakeUser($user);
		}
	}
    private function buildPosts($testdata) {
		foreach($testdata['posts'] as $key => $post) {
			$post['ID'] = $key;
			$this->posts[$key] = new FakePost($post);
		}
	}
    private function buildTaxonomies($testdata) {
		$this->taxonomy = [];
		foreach($testdata['terms'] as $key=>$term) {
			$theTerm = new FakeTerm($term);
			$this->taxonomy[$term[1]][$term[0]] = $theTerm;
        $this->allterms[$theTerm->term_id] = $theTerm;
		}
	}
	private function buildOneTaxonomy($term) {
		
	}

    function add_action($name,$value) {
        $this->actions[$name] = $value;
    }
    function add_shortcode($name,$value) {
    	$this->shortcodes[$name] = $value;
    }
    function add_filter($name,$value) {
    	$this->filters[$name] = $value;
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

    function get_the_title($post_id=0) {
    	if($post_id == 0) {
    		return $this->post->post_title;
    	} else {
    		return $this->posts[$post_id]->post_title;
    	}
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
    	$args[0] = rand();
    	$args[1] = $tax_type;
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
    function wp_get_current_user() {
    	return $this->current_user;
    }
    function get_user_meta($user_id, $meta) {
    	return $this->users[$user_id]->get_user_meta($meta);
    }
    function update_user_meta($user_id, $meta, $value) {
    	$this->users[$user_id]->update_meta($meta, $value);
    }
    function wp_set_current_user($user_id) {
    	$this->current_user = $this->users[$user_id];
    	 
    }
}
 