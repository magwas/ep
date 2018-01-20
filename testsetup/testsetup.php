<?php declare(strict_types=1);

/**
	Plugin Name: Elektori Parlamenttest setup (for internal use)
	Plugin URI: http://elektoriparlament.hu
	description: test data for end to end tests
	Version: 0.1.0
	Author: Magosányi Árpád <m4gw4s@gmail.com>
	License: GPL2
*/
function testsetup() {
	register_post_type(
		'szakkolegium', [
			'label'  => 'Szakkollégium',
			'public' => true,
		]
	);
	register_post_type(
		'problem', [
			'label'  => 'Problémafelvetés',
			'public' => true,
		]
	);
	register_post_type(
		'javaslat', [
			'label'  => 'Javaslat',
			'public' => true,
		]
	);
	register_taxonomy( 'szakkoli', [ 'szakkolegium', 'post', 'problem' ], [ 'label' => 'Szakkolégium' ] );
	register_taxonomy( 'vita', [ 'javaslat' ], [ 'label' => 'Javaslat' ] );
}
add_action( 'init', 'testsetup' );
