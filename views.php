<?php
function ep_plugin_before_content($post) {
	get_parent_szakkol($post);
}

function ep_plugin_after_content($post) {
	list_assets($post->post_name,'szakkolegium', '<h2>Ide tartozó szakkolégiumok:</h2>');
	list_assets($post->post_name,'post', '<h2>Programok:</h2>');
}
?>
