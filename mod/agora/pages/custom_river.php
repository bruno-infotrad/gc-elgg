<?php
elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('newsfeed');

                       $login_box = elgg_view('core/account/login_box');
                        $params = array(
                                        'content' => $content,
                                        'sidebar' => $login_box
                );
$login_box = elgg_view('core/account/login_box');

$db_prefix = elgg_get_config('dbprefix');
// full list
//$stream = elgg_list_river();
//No friends and no join group
$stream = elgg_list_river(array( 'wheres' => array("rv.type != 'user' AND rv.action_type != 'friend' AND rv.action_type != 'join'"),));
//$stream = elgg_list_river(array( 'wheres' => array("rv.action_type != 'friend' AND rv.action_type != 'join' AND rv.type != 'group' AND rv.action_type != 'create'  AND rv.action_type != 'update'"),));


elgg_set_page_owner_guid(1);
$content = elgg_view_layout('two_sidebar_river', array( 'title' => $title, 'sidebar'=> $login_box,'content' => $composer . $stream, 'sidebar_alt' => $activity,));

echo elgg_view_page($title, $content);
