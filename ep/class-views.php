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
	
    function _construct($structures,$dashboard) {
        $this->structures = $structures;
        $this->dashboard = $dashboard;
    }

    function init() {
        add_action('before_post_content', array($this, 'before_content'));
        add_action('after_post_content', array($this, 'after_content'));
        add_action('wp_footer', array($this, 'ep_footer'));
    }
	function before_content( $post ) {
		$this->structures->get_parent_by_taxonomy( $post, 'szakkoli', 'A <a href="%s/szakkolegium/%s">%s</a> alatt van.' );
		$this->structures->get_parent_by_taxonomy( $post, 'vita', 'A <a href="%s/problem/%s">%s</a> megoldási javaslata.' );
		$this->dasboard->show_dashboard();
	}
	
	
	function after_content( $post ) {
		global $szakkol_format;
		echo '<div class="et_pb_section et_section_regular">';
		echo '<div class="et_pb_row">';
		echo '<div class="et_pb_column_4_4">';
		echo '<div class="et_pb_portfolio_grid clearfix et_pb_module et_pb_bg_layout_light ">';
		$this->structures->list_assets_by_taxonomy( $post, 'szakkolegium', '<h2>Ide tartozó szakkolégiumok:</h2>', 'szakkoli', $this->szakkol_format );
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		$this->structures->list_assets_by_taxonomy( $post, 'post', '<h2>Programok:</h2>', 'szakkoli', '<a href="%s">%s</a><br>' );
		$this->structures->list_assets_by_taxonomy( $post, 'problem', '<h2>Problémafelvetések:</h2>', 'szakkoli', '<a href="%s">%s</a><br>' );
		$this->structures->list_assets_by_taxonomy( $post, 'javaslat', '<h2>Megoldási javaslatok:</h2>', 'vita', '<a href="%s">%s</a><br>' );
	}
	
	function ep_footer() {
		$me = new eDemo_SSOauth_Base();
		echo "<script type='text/javascript'>\n";
		echo 'jQuery("' . ADA_LOGIN_ELEMENT_SELECTOR . '").click(function(){' . str_replace( 'javascript:', '', $me->get_button_action( 'register' ) ) . ";});\n";
		echo 'jQuery("#login_button").click(function(){' . str_replace( 'javascript:', '', $me->get_button_action( 'register' ) ) . ";});\n";
		echo 'jQuery("' . ADA_LOGOUT_ELEMENT_SELECTOR . '").click(eDemo_SSO.adalogout);' . "\n";
		echo 'e=jQuery(".must-log-in").find("a")[0]; if(e) {e.href="' . $me->get_button_action( 'register' ) . '"};';
		echo "</script>\n";
	}
}
