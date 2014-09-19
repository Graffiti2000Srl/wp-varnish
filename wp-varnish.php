<?php

/*
 * Plugin Name: Wp Varnish
 * Author: Graffiti2000
 */

if (!defined('ABSPATH')) {
	die('Access denied.');
}

define('WPVA_NAME', 'WP Varnish');
define('WPVA_SLUG', 'wp-varnish');

require_once __DIR__ . '/inc/wpva.php';

$wpva = new WP_Varnish();
register_activation_hook(__FILE__, array($wpva, 'activate'));
register_deactivation_hook(__FILE__, array($wpva, 'deactivate'));