<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
declare(strict_types=1);

include_once 'ep/class-views.php';
require_once 'tests/class-wptestcase.php';
require_once 'tests/class-testdata.php';


class ViewsTest extends WPTestCase {

	public function setUp() {
		parent::setUp();
		$structures     = new Structures();
		$dashboard      = new Dashboard();
		$this->instance = new Views( $structures, $dashboard, new FakeUriGenerator() );
		$this->setData( ( new TestData() )->test_data );
	}

	public function testInitRegistersContentFilter() {
		$this->instance->init();
		$this->assertActionAdded( 'the_content', [ $this->instance, 'filter_content' ] );
	}

	public function testFilterAddsTheHeaderAndFooter() {
		$this->wp->current_post = $this->wp->posts[1];
		$this->assertEquals(
			'A <a href="http://example.org/szakkolegium/slug-6">An unknown post</a> alatt van.content<div class="et_pb_section et_section_regular">
<div class="et_pb_row">
<div class="et_pb_column_4_4">
<div class="et_pb_portfolio_grid clearfix et_pb_module et_pb_bg_layout_light "></div></div></div></div><h2>Megoldási javaslatok:</h2><a href="http://example.org/?post_type=javaslat&p=2">title_2</a><br><a href="http://example.org/?post_type=javaslat&p=4">title_4</a><br><a href="http://example.org/?post_type=javaslat&p=5">title_5</a><br>',
			$this->instance->filter_content( 'content' )
		);
	}

	public function testInitRegistersWpFooter() {
		$this->instance->init();
		$this->assertActionAdded( 'wp_footer', [ $this->instance, 'ep_footer' ] );
	}

	public function testEpFooterAddsTheScript() {
		define( 'ADA_LOGIN_ELEMENT_SELECTOR', 'loginSelector' );
		define( 'ADA_LOGOUT_ELEMENT_SELECTOR', 'logoutSelector' );
		$this->instance->ep_footer();
		$this->assertEquals(
			'<script type=\'text/javascript\'>
jQuery("loginSelector").click(function(){registerUri(blabla);});
jQuery("#login_button").click(function(){registerUri(blabla);});
jQuery("logoutSelector").click(eDemo_SSO.adalogout);
e=jQuery(".must-log-in").find("a")[0]; if(e) {e.href="registerUri(blabla)"};</script>
',
			$this->wp->output
		);
	}
}
