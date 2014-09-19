<?php

require_once __DIR__ . '/../g2k-plugin/g2k-settings.php';

/**
 * Class WP_Varnish_Settings
 *
 * @property string[] server_ips
 */
class WP_Varnish_Settings extends G2K_Settings {
	const REQUIRED_CAPABILITY = 'administrator';

	public function register_settings_pages() {
		add_menu_page('WP Varnish', 'WP Varnish', static::REQUIRED_CAPABILITY, $this->_plugin->prefix . '_settings', array($this, 'viewMainPage'));
		add_submenu_page($this->_plugin->prefix . '_settings', 'Selective Purge', 'Selective Purge', static::REQUIRED_CAPABILITY, $this->_plugin->prefix . '_settings_purge', array($this, 'viewPurgePage'));
	}

	public function viewMainPage() {
		if (current_user_can(static::REQUIRED_CAPABILITY)) {
			echo $this->_plugin->render_template('admin/settings/index.php');
		} else {
			wp_die('Access denied.');
		}
	}

	public function viewPurgePage() {
		if (current_user_can(static::REQUIRED_CAPABILITY)) {
			echo $this->_plugin->render_template('admin/settings/purge.php');
		} else {
			wp_die('Access denied.');
		}
	}

	public function register_settings() {
		add_settings_section($this->_plugin->prefix . '_section-server', 'Server Settings', array($this,  'viewSectionServer'), $this->_plugin->prefix . '_settings');
		add_settings_field($this->_plugin->prefix . '_field-server-ip', 'Server IPs', array($this, 'viewTextField'), $this->_plugin->prefix . '_settings', $this->_plugin->prefix . '_section-server', array('label' => $this->_plugin->prefix . '_field-server-ip'));
	}

	public function viewSectionServer($section) {
		echo $this->_plugin->render_template('admin/settings/section-header.php', array('section' => $section));
	}

	public function viewTextField($field) {
		echo $this->_plugin->render_template('admin/settings/field-text.php', array('settings' => $this->settings, 'field' => $field));
	}

	/**
	 * @return array
	 */
	protected function _get_default_settins() {
		return array(
			'server' => array(
				'ip' => '',
			),
		);
	}

	public function __get($name) {
		if ($name === 'server_ips') {
			$serverIPs = $this->settings['server']['ip'];
			$serverIPs = explode(',', $serverIPs);

			foreach ($serverIPs as &$serverIP) {
				$serverIP = trim($serverIP);
			}

			return $serverIPs;
		}

		return parent::__get($name);
	}
}