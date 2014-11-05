<?php
$preferred_tab = get_input('preferred_tab');
elgg_get_logged_in_user_entity()->preferred_tab = $preferred_tab;
system_message(elgg_echo('gc_theme:usersettings:save:ok'));
