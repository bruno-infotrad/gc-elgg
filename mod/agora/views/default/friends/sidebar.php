<?php
/**
 * Members sidebar
 */

// name search
$params = array(
	'method' => 'get',
	'action' => elgg_get_site_url() . 'friends/search/name',
	'disable_security' => true,
);
$body = elgg_view_form('friends/name_search', $params);

echo elgg_view_module('aside', '', $body);
