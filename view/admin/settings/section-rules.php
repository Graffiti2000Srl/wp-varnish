<form method="post" action="options.php">
	<?php settings_fields( $this->prefix . '_settings' ) ?>

	<table class="widefat <?= $this->prefix ?>_table" style="width: auto;" id="<?= $this->prefix ?>_rules_table">
		<thead>
		<tr>
			<th>Post type</th>
			<th>Url <small>(relative to the site url, for example "/" or "/category")</small></th>
			<th></th>
		</tr>
		</thead>

		<tfoot>
		<tr>
			<td colspan="3" style="text-align: right;">
				<input type="button" name="add" id="add" class="button-secondary" value="<?php esc_attr_e('Add rule') ?>">
				<input type="submit" name="submit" id="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</td>
		</tr>
		</tfoot>

		<tbody>
		<?php $types = get_post_types(); foreach ($this->_settings->rules as $index => $rule) : ?>
			<tr>
				<td><select name="<?= $this->prefix ?>_settings[rules][<?= $index ?>][type]"><?php foreach ($types as $type) : ?><option value="<?= $type ?>"<?= $rule['type'] === $type ? ' selected' : '' ?>><?= $type ?><?php endforeach ?></select></td>
				<td><input id="<?= $this->prefix ?>_settings[rules][<?= $index ?>][url]" name="<?= $this->prefix ?>_settings[rules][<?= $index ?>][url]" class="regular-text" value="<?php esc_attr_e($rule['url']) ?>"></td>
				<td><input type="button" class="button-secondary" value="-"></td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>

	<p>Possible wildcards:</p>
	<ul>
		<li>%post_id% <small>for the ID of the post</small></li>
		<li>%post_title% <small>for the title of the post</small></li>
		<li>%post_category% <small>for the categories of the post (can generate multiple purges)</small></li>
		<li>%post_tag% <small>for the tags of the post (can generate multiple purges)</small></li>
	</ul>
</form>
