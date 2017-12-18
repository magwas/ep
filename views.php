<?php
function ep_plugin_before_content($post) {
	get_parent_by_taxonomy($post, 'szakkoli', 'A <a href="%s/szakkolegium/%s">%s</a> alatt van.');
	get_parent_by_taxonomy($post, 'vita', 'A <a href="%s/problem/%s">%s</a> megoldási javaslata.');
}

function ep_plugin_after_content($post) {
	list_assets_by_taxonomy($post,'szakkolegium', '<h2>Ide tartozó szakkolégiumok:</h2>','szakkoli');
	list_assets_by_taxonomy($post,'post', '<h2>Programok:</h2>','szakkoli');
	list_assets_by_taxonomy($post,'problem', '<h2>Problémafelvetések:</h2>','szakkoli');
	list_assets_by_taxonomy($post,'javaslat', '<h2>Megoldási javaslatok:</h2>','vita');
}
?>
