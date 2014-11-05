<?php
/**
 * Elgg user display (details)
 * @uses $vars['entity'] The user entity
 */

if (elgg_is_xhr() && isset($vars['container_guid'])) {
        elgg_set_page_owner_guid($vars['container_guid']);
}
$user = get_entity($vars['container_guid']);

// SR [2012-11-30] Code moved to plugin hook handler in dfait_adsync module.
// // SR [2012-10-26] Call function to force a profile update.
// if (elgg_is_active_plugin('dfait_adsync')) {
// 	// file path to the page scripts
// 	$lib_path = elgg_get_plugins_path() . 'dfait_adsync/lib';
// 	require("$lib_path/functions.php");
	
// 	adsync_refresh_profile($user);
// }

if (elgg_is_active_plugin('profile_manager')) {

	echo "<div id=\"elgg-profile-items\">";
	echo "<dl class=\"elgg-profile\">";

	if ($user->isBanned()) {
		$about .= "<p class='profile-banned-user'>";
		$about .= elgg_echo('banned');
		$about .= "</p>";
	}
	
	$description_position = elgg_get_plugin_setting("description_position", "profile_manager");
	$show_profile_type_on_profile = elgg_get_plugin_setting("show_profile_type_on_profile", "profile_manager");
	
	if($description_position == "top"){
		echo $about;
	}
	echo '</dl>';
	
	// SR [2012-10-21] Display all fields even if they have no matching data.
	//
	// Returns all categories under $categorized_fields_all["categories"] and
	// all fields grouped by categories under $categorized_fields_all["fields"]
	// for the profile type of that $user
	$categorized_fields_all = profile_manager_get_categorized_fields($user, true, false, true);
	$cats_all = $categorized_fields_all['categories'];
	$fields_all = $categorized_fields_all['fields'];
	// Returns all filled categories under $categorized_fields["categories"] and
	// all filled fields grouped by categories under $categorized_fields["fields"]
	// for the user specified by $user
	$categorized_fields = profile_manager_get_categorized_fields($user);
	$cats = $categorized_fields['categories'];
	$fields = $categorized_fields['fields'];
	
	if($show_profile_type_on_profile != "no"){
		if($profile_type_guid = $user->custom_profile_type){
			if(($profile_type = get_entity($profile_type_guid)) && ($profile_type instanceof ProfileManagerCustomProfileType)){
				$details_result .= "<div class='even'><b>" . elgg_echo("profile_manager:user_details:profile_type") . "</b>: " . $profile_type->getTitle() . " </div>";
			}
		}
	}
	
	$show_header = true; // We show the category headers all the time.
	
	
// 	if(count($cats) > 0){
				
// 		// only show category headers if more than 1 category available
// 		if(count($cats) > 1){
// 			$show_header = true;
// 		} else {
// 			$show_header = false;
// 		}
		
		foreach($cats_all as $cat_guid => $cat){
			$cat_title = "";
			$field_result = "";
// 			$even_odd = "even";
			
			if($show_header){
				// make nice title
				if($cat_guid == -1){
					$title = elgg_echo("profile_manager:categories:list:system");
				} elseif($cat_guid == 0){
					if(!empty($cat)){
						$title = $cat;
					} else {
						$title = elgg_echo("profile_manager:categories:list:default");
					}
				} elseif($cat instanceof ProfileManagerCustomFieldCategory) {
					$title = $cat->getTitle();
				} else {
					$title = $cat;
				}
				
				$params = array(
					'text' => ' ',
					'href' => "#",
					'class' => 'elgg-widget-collapse-button',
					'rel' => 'toggle',
				);
				$collapse_link = elgg_view('output/url', $params);
				
				$cat_title = "<h3>" . $title . "</h3>\n";
			}
			
			foreach($fields_all[$cat_guid] as $field){
				
				$metadata_name = $field->metadata_name;
				
				if($metadata_name != "description"){
					// make nice title
					//$title = $field->getTitle();
					$title = elgg_echo("profile:$metadata_name");
					
					// get user value
					$value = $user->$metadata_name;
					if ($metadata_name == 'contactemail' && ! $value) {
						$value = $user->email;
					}
					
					// adjust output type
					if($field->output_as_tags == "yes"){
						$output_type = "tags";
						if (! preg_match('/,/',$value)){
							$value = $value.',';
						}
						$value = string_to_tag_array($value);
					} else {
						$output_type = $field->metadata_type;
					}
					
					if($field->metadata_type == "url"){
						$target = "_blank";
					} else {
						$target = null;
					}
					
					// SR [2012-10-21] Fix layout issue, without something in the previous field, the category title
					// will be centered, so we force a space for all fields if they are empty.
					if (empty($value)) {
						$value = "&nbsp;";
					}
					
					// SR [2012-10-21] Display manager name as a URL to the users profile.
					if("manager_name" == $metadata_name) {
						$output_type = "url";
						$site_url = elgg_get_site_url();
						$manager_id = $user->manager_dfaitedsid;
						$manager_url_vars = array(
												'text' => $value,
												'href' => $site_url . "profile/" . $manager_id,
						);
						
						// build result
						$field_result .= "<div class='elgg-profile-longfield'>";
						$field_result .= "<dt>" . $title . "</dt>";
						$field_result .= "<dd>".elgg_view("output/" . $output_type, $manager_url_vars)."</dd>";
						$field_result .= "</div>";
						
					} else {
						// build result
						$field_result .= "<div class='elgg-profile-longfield'>";
						$field_result .= "<dt>" . $title . "</dt>";
						$field_result .= "<dd>".elgg_view("output/" . $output_type, array("value" =>  $value, "target" => $target))."</dd>";
						$field_result .= "</div>";
					}
					
				}
			}
			
			if(!empty($field_result)){
				$details_result .= $cat_title;
				$details_result .= $field_result;	
				//$details_result .= "<div>" . $field_result . "</div>";	
			}
		}
// 	}
	
	if(!empty($details_result)){
		//echo "<div id='custom_fields_userdetails'>" . $details_result . "</div>";
		echo $details_result;
		if(elgg_get_plugin_setting("display_categories", "profile_manager") == "accordion"){
			?>
			<script type="text/javascript">
				$('#custom_fields_userdetails').accordion({
					header: 'h3',
					autoHeight: false
				});
			</script>
			<?php 
		}
	}
	
	if($description_position != "top"){
		echo $about;
	}

	echo '</div>';
	// SR [2012-09-11] put both avatar and profile completeness graph in a div.
	echo "<div id=\"elgg-profile-gadgets\" >";
	
	// SR [2012-09-10] If option set in Profile-Manager
	$enabled_profile_completeness_widget = elgg_get_plugin_setting('enable_profile_completeness_widget', 'profile_manager');
	if ($enabled_profile_completeness_widget) {

 		$profile_completeness_widget = elgg_view('profile/profile_completeness/content', $vars); // array('entity' => $vars['entity']));

		if (elgg_is_active_plugin('dfait_adsync')) {
 			echo "<div class=\"elgg-widget-handle clearfix\"><h3>" . elgg_echo("adsync:profile_completeness") . "</h3>";
		} else {
 			echo "<div class=\"elgg-widget-handle clearfix\"><h3>" . elgg_echo("gc_theme:profile_completeness") . "</h3>";
		}
		echo "<div id=\"elgg-profile-completeness\">";
		echo $profile_completeness_widget;
		echo "</div>";
		echo "</div>";
	}
/*	
	if (elgg_is_active_plugin('dfait_adsync')) {

		$nbToGet = 4;
		$hierarchy = adsync_get_hierarchy($user->user_dn, $nbToGet);
		$vars['hierarchy'] = $hierarchy;
	 	$hierarchy_widget = elgg_view('widgets/dfait_adsync_hierarchy/content', $vars);
		echo "<br/>";
	 	echo "<div id=\"elgg-teammates\"><h3>" . elgg_echo("adsync:hierarchy") . "</h3>";
 		echo $hierarchy_widget;
 		echo "</div>";
 			
		$teammates = adsync_get_teammates($user->manager_dn);
		$vars['teammates'] = $teammates;
		$teammates_widget = elgg_view('widgets/dfait_adsync_teammates/content', $vars);
		echo "<br/>";
		echo "<div id=\"elgg-teammates\"><h3>" . elgg_echo("adsync:teammates") . "</h3>";
		echo $teammates_widget;
		echo "</div>";
		
		$org_members = adsync_get_org_members($user->org_dn);
		$vars['org_members'] = $org_members;
		$org_members_widget = elgg_view('widgets/dfait_adsync_org_members/content', $vars);
		echo "<br/>";
		echo "<div id=\"elgg-org_members\"><h3>" . elgg_echo("adsync:org_members") . "</h3>";
		echo $org_members_widget;
		echo "</div>";
		
	}	
*/
	
	echo "</div>"; // End of div elgg-profile-gadgets
	
	
} else {
	$profile_fields = elgg_get_config('profile_fields');
	
	echo "<div id=\"elgg-profile-items\">";
	echo "<dl class=\"elgg-profile\">";
	if (is_array($profile_fields) && sizeof($profile_fields) > 0) {
		foreach ($profile_fields as $shortname => $valtype) {
			if ($shortname == "description") {
				// skip about me and put at bottom
				continue;
			}
			$value = $user->$shortname;
			if (!empty($value)) {
	?>
				<dt><?php echo elgg_echo("profile:{$shortname}"); ?></dt>
				<dd><?php echo elgg_view("output/{$valtype}", array('value' => $user->$shortname)); ?></dd>
	<?php
			}
		}
	}
	
	if (!elgg_get_config('profile_custom_fields')) {
		if ($user->isBanned()) {
			echo "</dl><p class='profile-banned-user'>";
			echo elgg_echo('banned');
			echo "</p>";
		} else {
			if ($user->description) {
				echo "<dt>" . elgg_echo("profile:aboutme") . "</dt>";
				echo "<dd>";
				echo elgg_view('output/longtext', array('value' => $user->description));
				echo "</dd></dl>";
			}
		}
	}
	echo "</div>";
	echo "<div id=\"elgg-profile-avatar\" style=\"min-height:200px;\">";
	echo elgg_view_entity_icon($user, 'large');
	echo "</div>";
}
