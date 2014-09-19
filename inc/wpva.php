<?php

require_once __DIR__ . '/../g2k-plugin/g2k-plugin.php';
require_once __DIR__ . '/wpva-settings.php';
require_once __DIR__ . '/wpva-admin-bar.php';

class WP_Varnish extends G2K_Plugin {
	public $name = 'WP Varnish';
	public $slug = 'wp-varnish';
	public $prefix = 'wpva';
	public $version = '0.0.1';

	/**
	 * @var WP_Varnish_Settings
	 */
	protected $_settings;
	/**
	 * @var WP_Varnish_Admin_Bar
	 */
	protected $_admin_bar;

	protected static $instance;

	public function __construct() {
		$this->_settings = new WP_Varnish_Settings($this);
		$this->_admin_bar = new WP_Varnish_Admin_Bar($this);
	}
}