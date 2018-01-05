<?php // phpcs:disable Squiz.Commenting


include_once 'ep/class-acceptrules.php';
require_once 'tests/class-wptestcase.php';
require_once 'tests/class-testdata.php';


class AcceptRulesTest extends WPTestCase {


	public function setUp() {
		parent::setUp();
        $this->instance=new AcceptRules();
        $this->setData((new TestData())->testData);
	}

	public function testAcceptRules() {
		$this->WP->wp_set_current_user( 1 );
		$this->assertEquals( array(), $this->WP->get_user_meta( 1, 'accepted_the_rules' ) );
		$this->instance->accept_rules();
		$this->assertEquals( 'user=1accepted=1', $this->WP->output);
		$this->assertDied();
		$this->assertEquals( 1, $this->WP->get_user_meta( 1, 'accepted_the_rules')[0] );
	}

	public function testAcceptRulesAjaxIsRegistered() {
		$this->instance->init();
		$this->assertActionAdded('wp_ajax_ep_accept_rules',array( $this->instance, 'accept_rules' ));
	}
	
}


