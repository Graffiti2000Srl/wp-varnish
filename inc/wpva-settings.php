<?php

require_once __DIR__ . '/../g2k-plugin/g2k-settings.php';

/**
 * Class WP_Varnish_Settings
 *
 * @property string[]   server_ips
 * @property WP_Varnish _plugin
 */
class WP_Varnish_Settings extends G2K_Settings {
	protected function _register_hooks() {
		parent::_register_hooks();

		add_action('admin_enqueue_scripts', array($this, 'load_resources'));
	}

	public function load_resources () {
		wp_enqueue_script($this->_plugin->prefix . '_settings', plugins_url('js/settings.js', dirname(__FILE__)), array('jquery'), $this->_plugin->version);
		wp_enqueue_style($this->_plugin->prefix . '_settings', plugins_url('css/settings.css', dirname(__FILE__)), array(), $this->_plugin->version);
	}

	public function register_settings_pages() {
		add_submenu_page('options-general.php', $this->_plugin->name . ' Settings', $this->_plugin->name, $this->_plugin->capability, $this->_plugin->prefix . '_settings', array($this, 'viewMainPage'));
	}

	public function viewMainPage() {
		if (current_user_can($this->_plugin->capability)) {
			$this->_plugin->managePurge();

			echo $this->_plugin->render_template('admin/settings/index.php');
		} else {
			wp_die('Access denied.');
		}
	}

	public function register_settings() {
		add_settings_section($this->_plugin->prefix . '_section-server', 'Server Settings', array($this, 'viewSectionServer'), $this->_plugin->prefix . '_settings');
		add_settings_field($this->_plugin->prefix . '_field-server-ip', 'Server IPs', array($this, 'viewTextField'), $this->_plugin->prefix . '_settings', $this->_plugin->prefix . '_section-server', array('label' => $this->_plugin->prefix . '_field-server-ip'));

		add_settings_section($this->_plugin->prefix . '_section-rules', 'Rules Settings', array($this, 'viewSectionServer'), $this->_plugin->prefix . '_rules');
		add_settings_field($this->_plugin->prefix . '_field-rules-types', 'Post types rules', array($this, 'viewTextField'), $this->_plugin->prefix . '_rules', $this->_plugin->prefix . '_section-rules', array('label' => $this->_plugin->prefix . '_field-rules-types'));
	}

	public function register_settings_addendum() {
		parent::register_settings_addendum();

		register_setting($this->_plugin->prefix . '_settings', $this->_plugin->prefix . '_rules', array($this, 'validate_settings'));
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
			'servers' => array(
				array(
					'ip'   => '127.0.0.1',
					'port' => '80',
				),
			),
			'rules' => array(
				array(
					'type' => '',
					'url'  => '',
				),
			),
		);
	}

	public function __get($name) {
		if ($name === 'servers') {
			return $this->settings['servers'];
		} elseif ($name === 'rules') {
			return $this->settings['rules'];
		}

		return parent::__get($name);
	}
}