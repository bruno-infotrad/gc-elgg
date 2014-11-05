<?php
if (elgg_is_admin_logged_in()) {
	$guid = (int) get_input('guid',0);
	$post = get_entity($guid);
	$post->exec_content = false;
	$guid = $post->save();
	system_message(elgg_echo("gc_theme:removed_exec_content"));
} else {
	system_message(elgg_echo("actionunauthorized"));
}
forward('/dashboard?page_type=all');
