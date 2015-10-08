<?php
/**
 * Personal notifications form body
 *
 * @uses $vars['user'] ElggUser
 */

/* @var ElggUser $user */
//$user = $vars['user'];
$user = elgg_get_logged_in_user_entity();

echo elgg_view('notifications/subscriptions/personal', $vars);
echo elgg_view('notifications/subscriptions/collections', $vars);
echo elgg_view('notifications/subscriptions/forminternals', $vars);

?>
<div class="elgg-foot">
<?php
echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $user->guid));
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
?>
</div>
