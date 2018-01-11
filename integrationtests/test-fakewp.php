<?php

require_once 'class-testbase.php';
require_once 'tests/fakes/class-fakewp.php';
require_once 'tests/class-testdata.php';

class FakeWPTest extends TestBase {

    function setUp() {
        if(!isset($this->WP)) {
            $this->WP = new FakeWP((new TestData())->testData);
            self::initialize ();
            $post = $this->WP->get_post($this->post_id);
            $post->data['thumbnail'] = "/tmp/foo.jpg";
        }
    }
    public function test_wp_die() {
        $this->WP->wp_die();
        $this->assertTrue($this->WP->died);
    }
    
    public function test_echo() {
        $this->WP->echo("hello");
        $this->assertEquals('hello',$this->WP->output);
    }
    
}
