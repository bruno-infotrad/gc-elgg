<?php
$pns_viewed = (int) get_input('pns_viewed');
$user = elgg_get_logged_in_user_entity();
$user->pns_viewed = $pns_viewed;
