<?php

$name = 'wpva_settings';
$value = '';

switch ($field['label']) {
	case 'wpva_field-server-ip':
		$name .= '[server][ip]';
		$value = $settings['server']['ip'];

		break;
}

?>

<input id="<?= esc_attr_e($name) ?>" name="<?= esc_attr_e($name) ?>" class="regular-text" value="<?= esc_attr_e($value) ?>">
<p class="description">Use comma "," to divide multiple servers</p>