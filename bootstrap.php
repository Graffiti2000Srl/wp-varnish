<?php

/*
 * Plugin Name: Wp Varnish
 * Version: 0.0.1
 * Author: <a href="http://graffiti.it">Graffiti2000</a>
 * Description: A plugin for purging Varnish cache when content is published or edited.
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