<?php

require_once __DIR__ . '/../g2k-plugin/g2k-admin-bar.php';

class WP_Varnish_Admin_Bar extends G2K_Admin_Bar {
	public function create_admin_bar(WP_Admin_Bar $wp_admin_bar) {
		$wp_admin_bar->add_node(array(
			'id'     => $this->_plugin->slug,
			'title'  => $this->_plugin->name,
			'href'   => admin_url('admin.php?page=' . $this->_plugin->slug),
		));

		$wp_admin_bar->add_node(array(
			'id'     => $this->_plugin->slug . '-purge-all',
			'parent' => $this->_plugin->slug,
			'title'  => 'Purge All Cache',
			'href'   => wp_nonce_url(admin_url('admin.php?page=' . $this->_plugin->slug . '&amp;' . $this->_plugin->prefix . '_purge_all&amp;noheader=true'), $this->_plugin->slug),
		));

		if ($post_id = static::_getPostID()) {
			$wp_admin_bar->add_node(array(
				'id'     => $this->_plugin->slug . '-purge-this',
				'parent' => $this->_plugin->slug,
				'title'  => 'Purge This Page',
				'href'   => wp_nonce_url(admin_url('admin.php?page=' . $this->_plugin->slug . '&amp;' . $this->_plugin->prefix . '_purge_this&amp;noheader=true&amp;post_id=' . $post_id), $this->_plugin->slug),
			));
		}
	}

	/**
	 * @return int
	 */
	protected static function _getPostID() {
		global $posts, $comment_post_ID, $post_ID;

		if ($post_ID) {
			return $post_ID;
		} elseif ($comment_post_ID) {
			return $comment_post_ID;
		} elseif (is_single() || is_page() && count($posts)) {
			return $posts[0]->ID;
		} elseif (isset($_REQUEST['p'])) {
			return (integer) $_REQUEST['p'];
		}

		return 0;
	}
}