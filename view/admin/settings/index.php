<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<?php settings_errors($this->slug) ?>
	<h2><?php esc_html_e( $this->name ); ?> Settings</h2>

	<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $this->prefix . '_settings'; ?>

	<h2 class="nav-tab-wrapper">
		<a href="?page=<?= $this->prefix ?>_settings&tab=<?= $this->prefix ?>_settings" class="nav-tab <?= $active_tab === $this->prefix . '_settings' ? 'nav-tab-active' : '' ?>">Server</a>
		<a href="?page=<?= $this->prefix ?>_settings&tab=<?= $this->prefix ?>_purge" class="nav-tab <?= $active_tab === $this->prefix . '_purge' ? 'nav-tab-active' : '' ?>">Purge</a>
	</h2>

	<?php if ($active_tab === $this->prefix . '_settings') : ?>
	<form method="post" action="options.php">
		<?php settings_fields( $this->prefix . '_settings' ); ?>
		<?php do_settings_sections( $this->prefix . '_settings' ); ?>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
		</p>
	</form>
	<?php elseif ($active_tab === $this->prefix . '_purge') : ?>
	<form method="post" action="">
		<?php wp_nonce_field($this->slug) ?>
		<p>
			Purge a URL: <input class="text" type="text" name="<?= $this->prefix ?>_purge_url" value="<?= get_bloginfo('url') . '/' ?>">
			<input type="submit" class="button-primary" name="<?= $this->prefix ?>_purge" value="Purge">
		</p>

		<p class="submit">
			<input type="submit" class="button-primary" name="<?= $this->prefix ?>_purge_all" value="Purge All Blog Cache"> Use only if necessary, and carefully as this will include a bit more load on varnish servers.
		</p>
	</form>
	<?php endif ?>
</div>