<?php
if (elgg_is_admin_logged_in() || roles_has_role(elgg_get_logged_in_user_entity(),'im_admin')) {
	$guid = (int) get_input('guid',0);
	$post = get_entity($guid);
	$post->exec_content = false;
	$guid = $post->save();
	system_message(elgg_echo("agora:removed_exec_content"));
} else {
	system_message(elgg_echo("actionunauthorized"));
}
forward('/dashboard?page_type=all');
