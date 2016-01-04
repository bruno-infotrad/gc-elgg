<?php
/**
* Profile Manager
*
* Overrules group edit form to support options (radio, dropdown, multiselect)
*
* @package profile_manager
* @author ColdTrick IT Solutions
* @copyright Coldtrick IT Solutions 2009
* @link http://www.coldtrick.com/
*/
$group = elgg_extract("entity", $vars);
if (empty($group)) {
	$new_group=true;
}
$name_limit = elgg_get_plugin_setting("group_limit_name", "profile_manager");
$description_limit = elgg_get_plugin_setting("group_limit_description", "profile_manager");
	
echo "<div>";
echo "<label>" . elgg_echo("groups:icon") . "</label><br />";
echo elgg_view("input/file", array('name' => 'icon'));
echo "</div>";

echo "<div>";
echo "<label>" . elgg_echo("groups:name") . '&nbsp;<span class="elgg-icon elgg-icon-info elgg-icon-info-top" title="'.elgg_echo('gc_theme:group_name:help').'"></span></label>';

$show_input = false;
if (empty($group) || ($name_limit === NULL) || ($name_limit === "") || elgg_is_admin_logged_in()) {
	$show_input = true;
}

if (!$show_input && !empty($group) && (!empty($name_limit) || ($name_limit == "0"))) {
	$name_limit = (int) $name_limit;
	$name_edit_count = (int) $group->getPrivateSetting("profile_manager_name_edit_count");

	if ($name_edit_count < $name_limit) {
		$show_input = true;
	}
	
	$name_edit_num_left = $name_limit - $name_edit_count;
}

if ($show_input) {
	if($new_group) {
		echo elgg_view("input/groups_autocomplete", array(
				'name' => 'name',
				'value' => elgg_extract('name', $vars),
		));
	} else {
		echo elgg_view("input/text", array(
				'name' => 'name',
				'value' => elgg_extract('name', $vars),
		));
		if (!empty($name_edit_num_left)) {
			echo "<div class='elgg-subtext'>" . elgg_echo("profile_manager:group:edit:limit", array("<strong>" . $name_edit_num_left . "</strong>")) . "</div>";
		}
	}
} else {
	// show value
	echo elgg_view("output/text", array(
			'value' => elgg_extract('name', $vars),
	));
	
	// add hidden so it gets saved and form checks still are valid
	echo elgg_view("input/hidden", array(
			'name' => 'name',
			'value' => elgg_extract('name', $vars),
	));
}

echo "</div>";

// retrieve group fields
$group_fields = profile_manager_get_categorized_group_fields();

if (count($group_fields["fields"]) > 0) {
	$group_fields = $group_fields["fields"];
	
	foreach ($group_fields as $field) {
		$metadata_name = $field->metadata_name;
		
		// get options
		$options = $field->getOptions();
		$placeholder = $field->getPlaceholder();
		
		// get type of field
		$valtype = $field->metadata_type;
		
		// get title
		$title = $field->getTitle();
		if($metadata_name == 'interests') {
			$title .= elgg_echo('tags:add_tip');
		}
		// get value
		$value = elgg_extract($metadata_name, $vars);
		
		$line_break = '<br />';
		if ($valtype == 'longtext') {
			$line_break = '';
		}
		echo '<div><label>';
		echo $title;
		echo "</label>";
		
		if ($hint = $field->getHint()) {
			?>
			<span class='custom_fields_more_info' id='more_info_<?php echo $metadata_name; ?>'></span>
			<span class="hidden" id="text_more_info_<?php echo $metadata_name; ?>"><?php echo $hint;?></span>
			<?php
		}
		
		echo $line_break;
		
		$field_output_options = array(
			'name' => $metadata_name,
			'value' => $value,
		);

		if ($options) {
			$field_output_options['options'] = $options;
		}

		if ($placeholder) {
			$field_output_options['placeholder'] = $placeholder;
		}

		if ($metadata_name == "description") {
		
			$show_input = false;
			if (empty($group) || ($description_limit === NULL) || ($description_limit === "") || elgg_is_admin_logged_in()) {
				$show_input = true;
			}
			
			$edit_num_left = 0;
			
			if (!$show_input && !empty($group) && (!empty($description_limit) || ($description_limit == "0"))) {
				$description_limit = (int) $description_limit;
				$field_edit_count = (int) $group->getPrivateSetting("profile_manager_description_edit_count");
			
				if ($field_edit_count < $description_limit) {
					$show_input = true;
				}
					
				$edit_num_left = $description_limit - $field_edit_count;
			}
			
			if ($show_input) {

				echo elgg_view("input/{$valtype}", $field_output_options);
				
				if (!empty($edit_num_left)) {
					echo "<div class='elgg-subtext'>" . elgg_echo("profile_manager:group:edit:limit", array("<strong>" . $edit_num_left . "</strong>")) . "</div>";
				}
			} else {
				// show value
				echo elgg_view("output/{$valtype}", array(
						'value' => $value
				));
					
				// add hidden so it gets saved and form checks still are valid
				echo elgg_view("input/hidden", array(
						'name' => $metadata_name,
						'value' => $value
				));
			}
		} elseif($metadata_name == 'interests') {
			echo elgg_view("input/group_tags_autocomplete", array('value' => elgg_extract('interests', $vars)));
		} else {
			if ($valtype == "dropdown") {
				// add div around dropdown to let it act as a block level element
				echo "<div>";
			}
			
			echo elgg_view("input/{$valtype}", $field_output_options);
			
			if ($valtype == "dropdown") {
				echo "</div>";
			}
		}
		
		echo '</div>';
	}
}