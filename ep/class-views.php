<?php // phpcs:disable Squiz.Commenting

class Views {
	private $szakkol_format = <<<'EOT'
<div class="project type-project status-publish has-post-thumbnail hentry et_pb_portfolio_item et_pb_grid_item">
	<a href="%1$s" title="%2$s"
		<span class="et_portfolio_image">
			%3$s<!-- image --!>
			<span class="et_overlay"></span>
		</span>
		</a>
	<h2>
		<a href="%1$s">%2$s</a>
	</h2>
</div>
EOT;
	
    function __construct($structures,$dashboard,$uriGenerator) {
        $this->structures = $structures;
        $this->dashboard = $dashboard;
        global $EP_WORLDPRESS_INTERFACE;
        $this->WP = & $EP_WORLDPRESS_INTERFACE;
        $this->uriGenerator = $uriGenerator;
    }

    function init() {
        $this->WP->add_action('the_content', array($this, 'filter_content'));
        $this->WP->add_action('wp_footer', array($this, 'ep_footer'));
    }
    
    function filter_content( $content ) {
    	$post = $this->WP->get_post();
    	return $this->before_content($post) .
    		$content .
    		$this->after_content($post);
    }
	function before_content( $post ) {
		$ret  = $this->structures->get_parent_by_taxonomy( $post, 'szakkoli', 'A <a href="%s/szakkolegium/%s">%s</a> alatt van.' );
		$ret .= $this->structures->get_parent_by_taxonomy( $post, 'vita', 'A <a href="%s/problem/%s">%s</a> megoldási javaslata.' );
		$ret .= $this->dashboard->show_dashboard();
		return $ret;
	}
	
	
	function after_content( $post ) {
		$ret = <<<'EOF'
<div class="et_pb_section et_section_regular">
<div class="et_pb_row">
<div class="et_pb_column_4_4">
<div class="et_pb_portfolio_grid clearfix et_pb_module et_pb_bg_layout_light ">
EOF;
		$ret .= $this->structures->list_assets_by_taxonomy( $post, 'szakkolegium', '<h2>Ide tartozó szakkolégiumok:</h2>', 'szakkoli', $this->szakkol_format );
		$ret .= '</div></div></div></div>';
		$ret .= $this->structures->list_assets_by_taxonomy( $post, 'post', '<h2>Programok:</h2>', 'szakkoli', '<a href="%s">%s</a><br>' );
		$ret .= $this->structures->list_assets_by_taxonomy( $post, 'problem', '<h2>Problémafelvetések:</h2>', 'szakkoli', '<a href="%s">%s</a><br>' );
		$ret .= $this->structures->list_assets_by_taxonomy( $post, 'javaslat', '<h2>Megoldási javaslatok:</h2>', 'vita', '<a href="%s">%s</a><br>' );
		return $ret;
	}
	
	function ep_footer() {
		$me = $this->uriGenerator;;
		$this->WP->echo("<script type='text/javascript'>\n");
		$this->WP->echo( 'jQuery("' . ADA_LOGIN_ELEMENT_SELECTOR . '").click(function(){' . str_replace( 'javascript:', '', $me->get_button_action( 'register' ) ) . ";});\n");
		$this->WP->echo( 'jQuery("#login_button").click(function(){' . str_replace( 'javascript:', '', $me->get_button_action( 'register' ) ) . ";});\n");
		$this->WP->echo( 'jQuery("' . ADA_LOGOUT_ELEMENT_SELECTOR . '").click(eDemo_SSO.adalogout);' . "\n");
		$this->WP->echo( 'e=jQuery(".must-log-in").find("a")[0]; if(e) {e.href="' . $me->get_button_action( 'register' ) . '"};');
		$this->WP->echo( "</script>\n");
	}
}
