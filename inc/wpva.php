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

	const PATTERN_ARCHIVE = '(?:page/[\d]+/)?$';

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
		$success = true;

		if (isset($_REQUEST[$this->prefix . '_purge_all']) and check_admin_referer( $this->slug )) {
			# Purge All
			$success = $this->purgeAll();
		} elseif (isset($_REQUEST[$this->prefix . '_purge_this']) and isset($_GET['post_id'])/* and check_admin_referer( $this->slug )*/) {
			# Purge Post
			$success = $this->purgeTaxonomiesByPost($_GET['post_id']);
		} elseif (isset($_REQUEST[$this->prefix . '_purge_url']) and check_admin_referer( $this->slug )) {
			# Purge Url
			$success = $this->purgeUrl($_REQUEST[$this->prefix . '_purge_url'] ?: $_SERVER['HTTP_REFERER']);
		} elseif (isset($_GET['purged'])) {
			if ($_GET['purged'] === 'true') {
				add_settings_error($this->slug, esc_attr('purged'), 'Purging done right', 'updated');
			} else {
				add_settings_error($this->slug, esc_attr('purged'), '<strong>ERROR</strong>: An error accurred while purging. Please use the purge button in the "Purge" tab to show the error');
			}

			return $_GET['purged'] === 'true';
		} else {
			return $success;
		}

		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			wp_redirect(admin_url('options-general.php?page=' . $this->prefix . '_settings&purged=' . ($success ? 'true' : 'false')));
			exit();
		}

		return $success;
	}

	public function purgeAll() {
		return $this->_purge('/.*');
	}

	public function purgeUrl($url) {
		$url = preg_replace( '#^https?://[^/]+#i', '', $url );
		return $this->_purge($url);
	}

	public function purgeFrontPage() {
		$success = $this->_purge('/' . static::PATTERN_ARCHIVE);

		if ( get_option('show_on_front', 'posts') == 'page' && intval(get_option('page_for_posts', 0)) > 0 ) {
			$posts_page_url = preg_replace( '#^https?://[^/]+#i', '', get_permalink(intval(get_option('page_for_posts'))) );
			$success &= $this->_purge( $posts_page_url . static::PATTERN_ARCHIVE );
		}

		return $success;
	}

	public function purgeFeed() {
		return $this->_purge('/feed/(?:(atom|rdf)/)?$');
	}

	public function purgeTaxonomiesByPost($post_id) {
		function mapName ($tax) {
			return $tax->name;
		}
		function mapSlug ($tax) {
			return $tax->rewrite->slug;
		}

		$taxObjects = get_taxonomies(array('show_ui' => true), 'objects');
		$taxonomies = wp_get_post_terms($post_id, array_map("mapName", $taxObjects));

		$slug = array();
		$queries = array();

		#TODO
		return true;
	}

	protected function _purge($url) {
		$servers = $this->_settings->servers;

		$regex = '/^(https?:\/\/)([^\/]+)(.*)/i';
		$host = preg_replace($regex, "$2", get_bloginfo('url'));
		$siteurl = preg_replace($regex, "$3", get_bloginfo('url'));
		$url = $siteurl . $url;

		$success = true;

		foreach ($servers as $server) {
			$success &= $this->_purgeServer($url, $host, $server);
		}

		return $success;
	}

	protected function _purgeServer($url, $host, $server) {
		$sock = fsockopen($server['ip'], $server['port'], $errno, $errstr, 5);
		if (!$sock) {
			add_settings_error($this->slug, esc_attr('purged'), '[' . $server['ip'] . '] <strong>ERROR</strong>: ' . $errstr . ' (' . $errno . ')');
			return false;
		}

		if ($server['admin'] === '1') {
			$buffer = fread($sock, 1024);

			if(preg_match('/(\w+)\s+Authentication required./', $buffer, $matches)) {
				fwrite($sock, 'auth ' . $server['secret'] . "\n");
				$buffer = fread($sock, 1024);

				var_dump($buffer);

				# TODO
			}
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
			add_settings_error($this->slug, esc_attr('purged'), '[' . $server['ip'] . '] Purging done right', 'updated');
			return true;
		} else {
			add_settings_error($this->slug, esc_attr('purged'), '[' . $server['ip'] . '] <strong>ERROR</strong>: Something went wrong <pre>' . $buffer . '</pre>');
			return false;
		}
	}
}