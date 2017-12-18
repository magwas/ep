<?php
function ep_plugin_before_content($post) {
	get_parent_szakkol($post);
}

function ep_plugin_after_content($post) {
echo 'AAA';
	list_assets_by_taxonomy($post->post_name,'szakkolegium', '<h2>Ide tartozó szakkolégiumok:</h2>','szakkoli');
	list_assets_by_taxonomy($post->post_name,'post', '<h2>Programok:</h2>','szakkoli');
	list_assets_by_taxonomy($post->post_name,'problem', '<h2>Problémafelvetések:</h2>','szakkoli');
	list_assets_by_taxonomy($post->post_name,'javaslat', '<h2>Megoldási javaslatok:</h2>','vita');
}
?>
