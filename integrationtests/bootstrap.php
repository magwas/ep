<?php declare(strict_types=1);
/**
 * PHPUnit bootstrap file
 *
 * @package Ep
 */

$_tests_dir = getenv( 'wp_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	throw new Exception( "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';
require '/tmp/wordpress/wp-content/plugins/eDemo-SSOauth/includes/class_edemo-ssoauth_base.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/ep/ep.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the wp testing environment.
require $_tests_dir . '/includes/bootstrap.php';
