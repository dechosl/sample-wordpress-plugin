<?php
defined('ABSPATH') or die('No script kiddies please!');
/**
    Plugin Name: Ned Test
    Plugin URI: https://www.ned-test-plugin.com/
    Description: Very simple plugin to test simple add/edit/delete interactions with Wordpress database.
    Author: Ned Andonov
    Version: 0.0.1
    Author URI: https://www.ned-test-plugin.com/
    Network: true
*/

// Declare plugin constants
if (!defined('NT_VERSION')) {
    define('NT_VERSION', '0.0.1');
}

if (!defined('NT_JS_DIR')) {
    define('NT_JS_DIR', plugin_dir_url(__FILE__) . 'js/');
}

if (!defined('NT_CSS_DIR')) {
    define('NT_CSS_DIR', plugin_dir_url(__FILE__) . 'css/');
}

if (!defined('NT_SETTINGS')) {
    define('NT_SETTINGS', 'nt-settings');
}

if (!defined('NT_ROOT_DIR')) {
    define('NT_ROOT_DIR', plugin_dir_path(__FILE__));
}

if (!defined('NT_FCPATH')) {
	define('NT_FCPATH', NT_ROOT_DIR . '/' . basename(__FILE__));
}

if (!class_exists('NT_APP')) {
    require_once 'lib/nt-app.php';
    $nt_object = new NT_APP();
}