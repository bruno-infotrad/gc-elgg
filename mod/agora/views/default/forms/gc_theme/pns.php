<?php
$now = time();
$user = elgg_get_logged_in_user_entity();
$content = "<div id='pns-header'>".elgg_echo('agora:pns_header')."</div>";
$content .= elgg_echo('agora:pns');
$content .= elgg_view('input/hidden', array('name' => 'pns_viewed', 'value' => $now));
$content .= elgg_view('input/submit', array(
        'value' => elgg_echo('agora:pns_accept'),
        'class' => 'elgg-button elgg-button-submit',
        'id' => 'select-pns',
	'onclick' => 'parent.$.colorbox.close();',
));
echo $content;
?>
