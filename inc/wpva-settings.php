<?php

require_once __DIR__ . '/../g2k-plugin/g2k-settings.php';

class WP_Varnish_Settings extends G2K_Settings {
	const REQUIRED_CAPABILITY = 'administrator';

	public function register_settings_pages() {
		add_menu_page('WP Varnish', 'WP Varnish', static::REQUIRED_CAPABILITY, $this->_plugin->slug, array($this, 'viewSettingsMainPage'));
		add_submenu_page($this->_plugin->slug, 'Selective Purge', 'Selective Purge', static::REQUIRED_CAPABILITY, $this->_plugin->slug . '-purge', array($this, 'viewSettingsPurgePage'));
	}

	public function viewSettingsMainPage() {
		if (current_user_can(static::REQUIRED_CAPABILITY)) {
			echo $this->_plugin->render_template('admin/settings/index.php');
		} else {
			wp_die('Access denied.');
		}
	}

	public function viewSettingsPurgePage() {
		if (current_user_can(static::REQUIRED_CAPABILITY)) {
			echo $this->_plugin->render_template('admin/settings/purge.php');
		} else {
			wp_die('Access denied.');
		}
	}

	public function register_settings() {
		// TODO: Implement _register_settings() method.
	}

	/**
	 * @return array
	 */
	protected function _get_default_settins() {
		return array();
	}
}