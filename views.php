<?php
function ep_plugin_before_content($post) {
	ElektoriParlament::get_parent_by_taxonomy($post, 'szakkoli', 'A <a href="%s/szakkolegium/%s">%s</a> alatt van.');
	ElektoriParlament::get_parent_by_taxonomy($post, 'vita', 'A <a href="%s/problem/%s">%s</a> megoldási javaslata.');
}

$szakkol_format = <<<'EOT'
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


function ep_plugin_after_content($post) {
	global $szakkol_format;
	echo '<div class="et_pb_section et_section_regular">';
	echo '<div class="et_pb_row">';
	echo '<div class="et_pb_column_4_4">';
	echo '<div class="et_pb_portfolio_grid clearfix et_pb_module et_pb_bg_layout_light ">';
	ElektoriParlament::list_assets_by_taxonomy($post,'szakkolegium', '<h2>Ide tartozó szakkolégiumok:</h2>','szakkoli', $szakkol_format);
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	ElektoriParlament::list_assets_by_taxonomy($post,'post', '<h2>Programok:</h2>','szakkoli', '<a href="%s">%s</a><br>');
	ElektoriParlament::list_assets_by_taxonomy($post,'problem', '<h2>Problémafelvetések:</h2>','szakkoli', '<a href="%s">%s</a><br>');
	ElektoriParlament::list_assets_by_taxonomy($post,'javaslat', '<h2>Megoldási javaslatok:</h2>','vita', '<a href="%s">%s</a><br>');
}

function ep_footer() {
	$me= new eDemo_SSOauth_Base();
	echo "<script type='text/javascript'>\n";
	echo 'jQuery(".menu-item-735").click(function(){' . str_replace("javascript:","",$me->get_button_action('register')) . ";});\n";
	echo 'jQuery(".menu-item-737").click(eDemo_SSO.adalogout);' . "\n";
	echo "</script>\n";
}
?>

