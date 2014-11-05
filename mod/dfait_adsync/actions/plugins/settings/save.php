<?php
$params = get_input('params');
$plugin_id = get_input('plugin_id');
$plugin = elgg_get_plugin_from_id($plugin_id);
$key = $CONFIG->dbpass;

if (!($plugin instanceof ElggPlugin)) {
	register_error(elgg_echo('plugins:settings:save:fail', array($plugin_id)));
	forward(REFERER);
}

$plugin_name = $plugin->getManifest()->getName();

$result = false;

// allow a plugin to override the save action for their settings
foreach ($params as $k => $v) {
	if ( ('adsync_teaminfo_password' == $k) || ('adsync_ldap_bind_password' == $k) ) {
		$block = mcrypt_get_block_size('tripledes', 'ecb');
		$pad = $block - (strlen($v) % $block);
		$v .= str_repeat(chr($pad), $pad);
		$v = base64_encode(mcrypt_encrypt(MCRYPT_3DES, $key, $v, MCRYPT_MODE_ECB));
	}
	$result = $plugin->setSetting($k, $v);
	if (!$result) {
		register_error(elgg_echo('plugins:settings:save:fail', array($plugin_name)));
		forward(REFERER);
		exit;
	}
}

system_message(elgg_echo('plugins:settings:save:ok', array($plugin_name)));
forward(REFERER);
