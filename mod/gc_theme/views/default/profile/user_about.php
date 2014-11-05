<?php
/**
 * Elgg user display (details)
 * @uses $vars['entity'] The user entity
 */

if (elgg_is_xhr() && isset($vars['container_guid'])) {
        elgg_set_page_owner_guid($vars['container_guid']);
}
$user = get_entity($vars['container_guid']);

	echo "<div id=\"elgg-profile-items\">";
	echo "<dl class=\"elgg-profile\">";

	if ($user->isBanned()) {
		$about .= "<p class='profile-banned-user'>";
		$about .= elgg_echo('banned');
		$about .= "</p>";
	} else {
		// SR [2012-10-21] Forcing the desciption 'About me' even if it contains no data.		
// 		if ($user->description) {
			$about.= "<dt>" . elgg_echo("profile:aboutme") . "</dt>";
			$about.= "<dd>";
			$about.= elgg_view('output/longtext', array('value' => ($user->description?:'&nbsp;')));
			$about.= "</dd>";
// 		}
	}
	
	$description_position = elgg_get_plugin_setting("description_position", "profile_manager");
	$show_profile_type_on_profile = elgg_get_plugin_setting("show_profile_type_on_profile", "profile_manager");
	
	if($description_position == "top"){
		echo $about;
	}
	echo '</dl>';
	echo "</div>";
