<form method="post" action="options.php">
	<?php settings_fields( $this->prefix . '_settings' ) ?>

	<table class="widefat <?= $this->prefix ?>_table" style="width: auto;" id="<?= $this->prefix ?>_settings_table">
		<thead>
		<tr>
			<th>Server IP</th>
			<th>Port</th>
			<th>Use Admin?</th>
			<th>Secret</th>
			<th></th>
		</tr>
		</thead>

		<tfoot>
		<tr>
			<td colspan="5" style="text-align: right;">
				<input type="button" name="add" id="add" class="button-secondary" value="<?php esc_attr_e('Add server') ?>">
				<input type="submit" name="submit" id="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</td>
		</tr>
		</tfoot>

		<tbody>
		<?php foreach ($this->_settings->servers as $index => $server) : ?>
			<!-- <?php var_dump($server) ?> -->
			<tr>
				<td><input id="<?= $this->prefix ?>_settings[servers][<?= $index ?>][ip]"     name="<?= $this->prefix ?>_settings[servers][<?= $index ?>][ip]"     class="regular-text" value="<?php esc_attr_e($server['ip']) ?>"></td>
				<td><input id="<?= $this->prefix ?>_settings[servers][<?= $index ?>][port]"   name="<?= $this->prefix ?>_settings[servers][<?= $index ?>][port]"   class="regular-text" value="<?php esc_attr_e($server['port']) ?>" style="width: 5em;"></td>
				<td><input id="<?= $this->prefix ?>_settings[servers][<?= $index ?>][admin]"  name="<?= $this->prefix ?>_settings[servers][<?= $index ?>][admin]"  type="checkbox"      value="1" <?= esc_attr__($server['admin']) == '1' ? ' checked' : '' ?>></td>
				<td><input id="<?= $this->prefix ?>_settings[servers][<?= $index ?>][secret]" name="<?= $this->prefix ?>_settings[servers][<?= $index ?>][secret]" class="regular-text" value="<?php esc_attr_e($server['secret']) ?>"></td>
				<td><input type="button" class="button-secondary" value="-"></td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
</form>
