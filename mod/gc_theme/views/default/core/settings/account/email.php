<?php
/**
 * Override to not display email box is syncing with AD
 *
 */

$user = elgg_get_page_owner_entity();

if ($user) {
?>
<div class="elgg-module elgg-module-info">
	<?php
	if (! elgg_is_active_plugin('dfait_adsync')) {
		echo '<div class="elgg-head">';
		echo '<h3>';
		echo elgg_echo('email:settings');
		echo '</h3>';
		echo '</div>';
		echo '<div class="elgg-body"><p>';
		echo elgg_echo('email:address:label');
		echo elgg_view('input/email',array('name' => 'email', 'value' => $user->email));
		echo '</p></div>';
	} else {
		echo elgg_view('input/hidden',array('name' => 'email', 'value' => $user->email));
	}
	?>
</div>
<?php
}
