<form method="post" action="options-general.php?page=<?= $this->prefix ?>_settings">
	<?php wp_nonce_field($this->slug) ?>
	<p>
		Purge a URL: <input class="text" type="text" name="<?= $this->prefix ?>_purge_url" value="<?= get_bloginfo('url') . '/' ?>">
		<input type="submit" class="button-primary" name="<?= $this->prefix ?>_purge" value="Purge">
	</p>

	<p class="submit">
		<input type="submit" class="button-primary" name="<?= $this->prefix ?>_purge_all" value="Purge All Blog Cache"> Use only if necessary, and carefully as this will include a bit more load on varnish servers.
	</p>
</form>
