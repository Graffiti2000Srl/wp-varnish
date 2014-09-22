<?php

require_once __DIR__ . '/../g2k-plugin/g2k-plugin.php';
require_once __DIR__ . '/wpva-settings.php';
require_once __DIR__ . '/wpva-admin-bar.php';

class WP_Varnish extends G2K_Plugin {
	public $name = 'WP Varnish';
	public $slug = 'wp-varnish';
	public $prefix = 'wpva';
	public $version = '0.0.1';
	public $capability = 'administrator';

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

	public function managePurge() {
		if (isset($_REQUEST[$this->prefix . '_purge_all']) and check_admin_referer( $this->slug )) {
			# Purge All
			$this->purgeAll();
		} elseif (isset($_REQUEST[$this->prefix . '_purge_this']) and isset($_GET['post_id']) and check_admin_referer( $this->slug )) {
			# Purge Post
			var_dump('2');
		} elseif (isset($_REQUEST[$this->prefix . '_purge_url']) and isset($_GET['purge_url']) and check_admin_referer( $this->slug )) {
			# Purge Url
			$this->purgeUrl($_GET['purge_url']);
		}
	}

	public function purgeAll() {
		$this->_purge('/.*');
	}

	public function purgeUrl($url) {
		$url = preg_replace( '#^https?://[^/]+#i', '', $url );
		$this->_purge($url);
	}

	protected function _purge($url) {
		$servers = $this->_settings->server_ips;

		$regex = '/^https?:\/\/([^\/]+)(.*)/i';
		$host = preg_replace($regex, "$1", get_bloginfo('url'));
		$siteurl = preg_replace($regex, "$2", get_bloginfo('url'));
		$url = $siteurl . $url;


		foreach ($servers as $server) {
			$this->_purgeServer($url, $host, $server);
		}
	}

	protected function _purgeServer($url, $host, $server) {
		$sock = fsockopen($server, 80, $errno, $errstr, 5);
		if (!$sock) {
			add_settings_error($this->slug, esc_attr('purged'), '[' . $server . '] <strong>ERROR</strong>: ' . $errstr . ' (' . $errno . ')');
			return false;
		}

		$out  = "BAN $url HTTP/1.0\r\n";
		$out .= "Host: $host\r\n";
		$out .= "User-Agent: WP-Varnish plugin\r\n";
		$out .= "Connection: Close\r\n\r\n";

		fwrite($sock, $out);

		$buffer = fread($sock, 1024);
		$bufferArray = explode("\n", $buffer);

		fclose($sock);

		if (preg_match('/200/', $bufferArray[0])) {
			add_settings_error($this->slug, esc_attr('purged'), '[' . $server . '] Purging done right', 'updated');
			return true;
		} else {
			add_settings_error($this->slug, esc_attr('purged'), '[' . $server . '] <strong>ERROR</strong>: Something went wrong <pre>' . $buffer . '</pre>');
			return false;
		}
	}
}