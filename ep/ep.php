<?php declare(strict_types=1);

/**
	Plugin Name: Elektori Parlament
	Plugin URI: http://elektoriparlament.hu
	description: Az elektori parlament cuccai
	Version: 0.1.0
	Author: Magosányi Árpád <m4gw4s@gmail.com>
	License: GPL2
*/

include_once 'class-wpinterface.php';
//phpcs:disable WordPress.NamingConventions.ValidVariableName.NotSnakeCase
global $_ep_wordpress_interface;
if ( ! isset( $_ep_wordpress_interface ) ) {
	$_ep_wordpress_interface = new WPInterface();
}

include_once 'class-structures.php';
include_once 'class-dashboard.php';
include_once 'class-views.php';
include_once 'class-vote.php';
include_once 'class-acceptrules.php';
include_once 'class-fixcommentreply.php';
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

ep_bootstrap();

function ep_bootstrap() {

	$plugin_version = '0.1.0';
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$structures  = new Structures();
		$acceptrules = new AcceptRules( $structures );
		$acceptrules->init();
		return;
	}
	if ( version_compare( PHP_VERSION, '7.0.0', '>=' ) ) {
		if ( ! defined( 'EP_VERSION' ) ) {
			define( 'EP_VERSION', $plugin_version );
		}
		$structures    = new Structures();
		$dashboard     = new Dashboard( $structures );
		$uri_generator = new eDemo_SSOauth_Base();
		$views         = new Views( $structures, $dashboard, $uri_generator );
		$vote          = new Vote( $structures );
		$acceptrules   = new AcceptRules( $structures );
		$commentreply  = new FixCommentReply( $uri_generator );
		$structures->init();
		$dashboard->init();
		$views->init();
		$vote->init();
		$acceptrules->init();
		$commentreply->init();
	} else {
		register_activation_hook( __FILE__, 'ep_php_version_too_low' );
	}
}

if ( ! function_exists( 'ep_php_version_too_low' ) ) {
	function ep_php_version_too_low() {
		die( 'The <strong>Elektori Parlament</strong> plugin requires PHP version 7.0.0 or greater.' );
	}
}

