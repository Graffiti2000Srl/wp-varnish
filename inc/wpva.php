<?php

require_once __DIR__ . '/../g2k-plugin/g2k-plugin.php';
require_once __DIR__ . '/wpva-settings.php';

class WP_Varnish extends G2K_Plugin {
	public $slug = 'wp-varnish';
	public $prefix = 'wpva';
	public $version = '0.0.1';

	/**
	 * @var WP_Varnish_Settings
	 */
	protected $_settings;

	protected static $instance;

	public function __construct() {
		$this->_settings = new WP_Varnish_Settings($this);
	}
}