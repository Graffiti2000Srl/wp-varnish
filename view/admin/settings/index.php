<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<?php settings_errors($this->slug) ?>
	<h2><?php esc_html_e( $this->name ); ?> Settings</h2>

	<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $this->prefix . '_settings'; ?>

	<h2 class="nav-tab-wrapper">
		<a href="?page=<?= $this->prefix ?>_settings&tab=<?= $this->prefix ?>_settings" class="nav-tab <?= $active_tab === $this->prefix . '_settings' ? 'nav-tab-active' : '' ?>">Server</a>
		<a href="?page=<?= $this->prefix ?>_settings&tab=<?= $this->prefix ?>_rules" class="nav-tab <?= $active_tab === $this->prefix . '_rules' ? 'nav-tab-active' : '' ?>">Rules</a>
		<a href="?page=<?= $this->prefix ?>_settings&tab=<?= $this->prefix ?>_purge" class="nav-tab <?= $active_tab === $this->prefix . '_purge' ? 'nav-tab-active' : '' ?>">Purge</a>
	</h2>

	<?php

	if ($active_tab === $this->prefix . '_settings') {
		echo $this->render_template('admin/settings/section-server.php');
	} elseif ($active_tab === $this->prefix . '_rules') {
		echo $this->render_template('admin/settings/section-rules.php');
	} elseif ($active_tab === $this->prefix . '_purge') {
		echo $this->render_template('admin/settings/section-purge.php');
	}

	?>
</div>