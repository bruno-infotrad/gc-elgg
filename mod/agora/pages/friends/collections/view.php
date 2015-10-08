<?php
/**
 * Elgg collections of friends
 *
 * @package Elgg.Core
 * @subpackage Social.Collections
 */

$title = elgg_echo('friends:collections');
elgg_register_title_button('collections', 'add');
$content = elgg_view_access_collections(elgg_get_logged_in_user_guid());
$params = array(
        'content' => $content,
        'sidebar' => elgg_view('members/sidebar'),
        'title' => $title,
        'filter_override' => elgg_view('members/nav', array('selected' => 'collections')),
);
$body = elgg_view_layout('content', $params);
echo elgg_view_page($title, $body);
