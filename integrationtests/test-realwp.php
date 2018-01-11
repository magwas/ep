<?php

require_once 'class-testbase.php';

class RealWPTest extends TestBase {

	function setUp() {
		parent::setUp();
		if(!isset($this->WP)) {
		    $this->WP = new WPInterface();
		    register_taxonomy( 'szakkol', 'szakkol' );
		    $this->initialize ();
		    $attachment = array(
		        'post_mime_type' => 'image/jpeg',
		        'post_title' => "/tmp/foo.jpg",
		        'post_content' => '',
		        'post_status' => 'inherit'
		    );
		    $attach_id = wp_insert_attachment( $attachment, "/tmp/foo.jpg", $this->post_id );
		    set_post_thumbnail( $this->post_id, $attach_id );
		}
	}
	public function test_wp_die() {
	    $this->setExpectedException( 'WPDieException' );
	    $this->WP->wp_die();
	}
	
	public function test_echo() {
	    $this->expectOutputString('hello');
	    $this->WP->echo("hello");
	}
}
