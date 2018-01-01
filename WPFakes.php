<?php
declare(strict_types=1);

class WPFakes
{

	public static $user;
	public static $actions;
	public static $usermeta;
	public static $oldmeta;
	public static $died=false;

	public function add_action($name, $action) {
		self::$actions[$name] = $action;
	}
	public function get_user_meta($userid, $key) {
		if (!array_key_exists($userid,self::$usermeta)) return;
		if (!array_key_exists($key,self::$usermeta[$userid])) return;
		return self::$usermeta[$userid][$key];
	}

	public function update_user_meta($id, $key, $value) {
		self::$oldmeta=self::$usermeta[$id];
		self::$usermeta[$id][$key]=$value;
	}
	public function wp_die() {
		self::$died=true;
	}
}

function add_action($name,$action) {
	WPFakes::add_action($name,$action);
}

function get_user_meta($user,$key) {
	return WPFakes::get_user_meta($user,$key);
}

function wp_get_current_user() {
	return WPFakes::$user;
}

function update_user_meta($id, $key, $value) {
	return WPFakes::update_user_meta($id, $key, $value);
}
function wp_die() {
	return WPFakes::wp_die();
}

?>
